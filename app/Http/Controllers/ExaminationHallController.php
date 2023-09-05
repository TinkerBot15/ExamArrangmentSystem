<?php

namespace App\Http\Controllers;

use App\Models\ExaminationHall;
use App\Models\Student;
use Twilio\Rest\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class ExaminationHallController extends Controller
{
    // Display a listing of the examination halls
        public function index()
    {
        $examinationHalls = ExaminationHall::all();
        return view('examination_halls.index', compact('examinationHalls'));
    }


    // Show the form for creating a new examination hall
    public function create()
    {
        return view('examination_halls.create');
    }

    // Store a newly created examination hall in storage
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'seating_capacity' => 'required|numeric',
            'rows' => 'required|numeric',
            'columns' => 'required|numeric'
        ]);

        if ($validator->fails()) {
            return redirect('examination_halls/create')
                        ->withErrors($validator)
                        ->withInput();
        }


        $examinationHall = ExaminationHall::create([
            'name' => $request->name,
            'seating_capacity' => $request->seating_capacity,
            'rows' => $request->rows,
            'columns' => $request->columns,
        ]);

        \Log::debug(print_r($examinationHall->toArray(), true));

        return redirect()->route('examination_halls.index')->with('success', 'Examination Hall created successfully');
    }

    // Show the form for editing the specified examination hall
    public function edit($id)
    {
        $examinationHall = ExaminationHall::findOrFail($id);
        return view('examination_halls.edit', compact('examinationHall'));
    }



    // Update the specified examination hall in storage
    public function update(Request $request, ExaminationHall $hall)
    {
        $request->validate([
            'name' => 'required|unique:examination_halls,name,'.$hall->id,
            'seating_capacity' => 'required|integer|min:1',
            'rows' => 'required|numeric',
            'columns' => 'required|numeric'
        ]);

        $hall->update($request->all());

        return redirect()->route('examination_halls.index')
            ->with('success', 'Examination hall updated successfully.');
    }

    public function show($id)
{
    $hall = ExaminationHall::findOrFail($id);

    return view('examination_halls.show', compact('hall'));
}

public function generateSeatingArrangement(Request $request, $examHallId)
    {
        $examHall = ExaminationHall::findOrFail($examHallId);
        $seatingCapacity = $examHall->seating_capacity;
        $seatingArrangement = json_decode($examHall->seating_arrangement, true);

        $courses = $request->input('courses');
        $students = Student::whereIn('course_code', $courses)->get();

        // Build a graph to represent the seating arrangement
        $graph = [];
        for ($i = 1; $i <= $seatingCapacity; $i++) {
            for ($j = 1; $j <= $seatingArrangement['columns']; $j++) {
                $seatNumber = "{$i}_{$j}";
                $graph[$seatNumber] = [];
                if ($j > 1) {
                    $leftSeat = "{$i}_" . ($j - 1);
                    $graph[$seatNumber][] = $leftSeat;
                }
                if ($i > 1) {
                    $topSeat = ($i - 1) . "_{$j}";
                    $graph[$seatNumber][] = $topSeat;
                }
                if ($j < $seatingArrangement['columns']) {
                    $rightSeat = "{$i}_" . ($j + 1);
                    $graph[$seatNumber][] = $rightSeat;
                }
                if ($i < $seatingArrangement['rows']) {
                    $bottomSeat = ($i + 1) . "_{$j}";
                    $graph[$seatNumber][] = $bottomSeat;
                }
            }
        }

        // Assign unique colors to each course
        $colors = [];
        foreach ($courses as $course) {
            $colors[$course] = $this->getRandomColor();
        }

        // Color the vertices of the graph using the graph coloring algorithm
        $coloredSeats = $this->colorGraph($graph, $colors);

        // Save the colored seating arrangement to the database
        $examHall->seating_arrangement = json_encode($coloredSeats);
        $examHall->save();

        // Send a notification to the students with their seating details
        foreach ($students as $student) {
            $seatNumber = $student->seat_number;
            $course = $student->course_code;
            $color = $colors[$course];
            // Send SMS notification to the student with their seating details
            $this->sendNotification($student->phone_number, "Your seat number is $seatNumber and your exam session color is $color");
        }


    }



    // Remove the specified examination hall from storage
    public function destroy(ExaminationHall $hall)
    {
        $hall->delete();

        return redirect()->route('examination_halls.index')
            ->with('success', 'Examination hall deleted successfully.');
    }

    


    public function sendSmsNotification()
    {
        // Twilio account SID and auth token
        $accountSid = getenv("TWILIO_ACCOUNT_SID");
        $authToken = getenv("TWILIO_AUTH_TOKEN");

        // Twilio phone number
        $twilioNumber = getenv("TWILIO_PHONE_NUMBER");

        // Initialize Twilio client
        $client = new Client($accountSid, $authToken);

        // Send SMS notification to each student
        $students = Student::all();
        foreach ($students as $student) {
            $message = "Dear " . $student->name . ", your exam is scheduled for " . $student->exam_date_time . " at " . $student->exam_venue . ". Please arrive at least 30 minutes before the exam starts.";

            $client->messages->create(
                $student->phone_number,
                [
                    "from" => $twilioNumber,
                    "body" => $message
                ]
            );
        }

        return "SMS notifications sent successfully.";
    }

    public function getSeatingArrangement($examHallId)
{
    $examHall = ExaminationHall::findOrFail($examHallId);
    $seatingCapacity = $examHall->seating_capacity;
    $seatingArrangement = json_decode($examHall->seating_arrangement, true);
    
    return [
        'seating_capacity' => $seatingCapacity,
        'seating_arrangement' => $seatingArrangement
    ];
}

public function getStudentsByExamHall($examHallId)
{
    // Retrieve the exam hall and its seating arrangement
    $examHall = ExaminationHall::findOrFail($examHallId);
    $seatingArrangement = json_decode($examHall->seating_arrangement, true);

    // Retrieve the courses offered in the exam hall
    $courses = [];
    foreach ($seatingArrangement as $row) {
        foreach ($row as $seat) {
            if (!in_array($seat['course'], $courses)) {
                $courses[] = $seat['course'];
            }
        }
    }

    // Retrieve the students taking exams in the exam hall
    $students = Student::whereIn('course_code', $courses)->get();

    return $students;
}

/**
 * Creates a graph object that represents the seating arrangement of the exam hall
 * where the seats are the vertices and the edges represent the adjacency of the seats.
 *
 * @param array $seatingArrangement
 * @return array
 */
public function createGraph(array $seatingArrangement): array
{
    $graph = [];
    for ($i = 1; $i <= $seatingArrangement['rows']; $i++) {
        for ($j = 1; $j <= $seatingArrangement['columns']; $j++) {
            $seatNumber = "{$i}_{$j}";
            $graph[$seatNumber] = [];
            if ($j > 1) {
                $leftSeat = "{$i}_" . ($j - 1);
                $graph[$seatNumber][] = $leftSeat;
            }
            if ($i > 1) {
                $topSeat = ($i - 1) . "_{$j}";
                $graph[$seatNumber][] = $topSeat;
            }
            if ($j < $seatingArrangement['columns']) {
                $rightSeat = "{$i}_" . ($j + 1);
                $graph[$seatNumber][] = $rightSeat;
            }
            if ($i < $seatingArrangement['rows']) {
                $bottomSeat = ($i + 1) . "_{$j}";
                $graph[$seatNumber][] = $bottomSeat;
            }
        }
    }
    return $graph;
}

private function assignColorsToCourses($students) {
    $colors = [];
    foreach ($students as $student) {
        $course = $student->course_code;
        if (!array_key_exists($course, $colors)) {
            $colors[$course] = $this->getRandomColor();
        }
    }
    return $colors;
}

public function colorVertices($graph, $colors) {
    // Initialize an empty array to hold the colors of each vertex
    $coloredVertices = [];

    // Initialize all vertices to have no color
    foreach ($graph as $vertex => $adjacentVertices) {
        $coloredVertices[$vertex] = null;
    }

    // Color the vertices using the graph coloring algorithm
    foreach ($graph as $vertex => $adjacentVertices) {
        // Get the colors of all adjacent vertices
        $adjacentColors = [];
        foreach ($adjacentVertices as $adjacentVertex) {
            if (isset($coloredVertices[$adjacentVertex])) {
                $adjacentColor = $coloredVertices[$adjacentVertex];
                if ($adjacentColor !== null) {
                    $adjacentColors[] = $adjacentColor;
                }
            }
        }

        // Find the first available color that is not in the adjacent colors
        $availableColors = array_diff($colors, $adjacentColors);
        if (count($availableColors) > 0) {
            $coloredVertices[$vertex] = reset($availableColors);
        } else {
            // If all colors are taken, assign a random color
            $coloredVertices[$vertex] = $this->getRandomColor();
        }
    }

    return $coloredVertices;
}

public function saveSeatingArrangement($examHallId, $seatingArrangement)
{
    $examHall = ExaminationHall::findOrFail($examHallId);
    $examHall->seating_arrangement = json_encode($seatingArrangement);
    $examHall->save();

    return $seatingArrangement;
}

public function showSeatingArrangement($examHallId)
{
    // Retrieve the seating arrangement for the given exam hall id
    $seatingArrangement = $this->getSeatingArrangement($examHallId);

    // Retrieve the list of students taking exams in the exam hall
    $students = $this->getStudentsByExamHall($examHallId);

    // Create a graph object that represents the seating arrangement
    $graph = $this->createGraph($seatingArrangement);

    // Assign a unique color to each course
    $colors = $this->assignColorsToCourses($students);

    // Color the vertices of the graph using the graph coloring algorithm
    $coloredGraph = $this->colorVertices($graph, $colors);

    // Save the colored seating arrangement in the database
    $savedArrangement = $this->saveSeatingArrangement($examHallId, $coloredGraph);

    // Return the view with the seating arrangement data
    return view('showSeatingArrangement', [
        'seatingArrangement' => $savedArrangement,
        'students' => $students,
    ]);
}

public function sendSMSNotifications($timetableId)
{
    // Fetch the student data and generate the seating arrangement
    $studentData = $this->getStudentData($timetableId);

    $seatingArrangements = SeatingArrangement::where('examination_timetable_id', $timetableId)->get();

    // Get the exam start time and calculate the notification time
    $timetable = ExaminationTimetable::findOrFail($timetableId);
    $examStartTime = Carbon::parse($timetable->exam_start_time); // Replace with the actual exam start time
    $notificationTime = $examStartTime->subMinutes(5);

    $apiKey = env('TERMII_API_KEY');
    $senderID = env('SENDER_ID');

    // Loop through the student data and schedule SMS notifications
    foreach ($seatingArrangements as $seatingArrangement) {
        // Check if the SMS notification has already been sent to this phone number
        $existingNotification = SeatingArrangement::where('examination_timetable_id', $timetableId)
            ->where('phone_number', $seatingArrangement->phone_number)
            ->exists();

        if ($existingNotification) {
            continue; // Skip sending SMS for this recipient
        }

        // Get the student's seating details from the seating arrangement

        // Customize the message content based on the student's details
        $message = "Dear {$seatingArrangement->student_name}, your exam details: Course: {$seatingArrangement->course}, ({$seatingArrangement->course_code}), Hall: {$seatingArrangement->hall_name}, and your Seat Number: {$seatingArrangement->seat_number}. Good luck!";

        // Send the SMS notification using the Termii API
        $client = new \GuzzleHttp\Client();

        $data = [
            'to' => $seatingArrangement->phone_number,
            'from' => 'E-Seating', // Replace with your Termii sender ID or phone number
            'sms' => $message,
            'api_key' => $apiKey,
            'channel' => 'generic',
            'type' => 'plain'
        ];

        // Send the SMS notification using the Termii API
        $response = $client->request('POST', 'https://api.ng.termii.com/api/sms/send', [
            'headers' => [
                'Content-Type' => 'application/json',
            ],
            'json' => $data,
        ]);
    }

    // Redirect back to the seating arrangement page with a success message
    return redirect()->back()->with('success', 'Text Messages sent successfully.');
}







}
