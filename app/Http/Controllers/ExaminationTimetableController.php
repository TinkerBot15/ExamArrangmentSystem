<?php

namespace App\Http\Controllers;

use App\Graph\Graph;
use Carbon\Carbon;
use App\Models\ExaminationTimetable;
use App\Models\User;
use App\Models\Student;
use App\Models\Course;
use App\Models\SeatingArrangement;
use App\Models\ExaminationHall;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ExamController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Services\TwilioService;
use Dompdf\Dompdf;
use Illuminate\Support\Facades\Gate;
use GuzzleHttp\Client;




class ExaminationTimetableController extends Controller
{
    public function index()
    {
        $examination_timetables = ExaminationTimetable::with('examinationHall')->get();
        return view('examination_timetable.index', compact('examination_timetables'));
    }


    public function create()
    {
        $courses = Course::pluck('code', 'id');
        $halls = ExaminationHall::pluck('name', 'id');
        $invigilators = User::where('role', 'invigilator')->get();

        return view('examination_timetable.create', compact('courses', 'halls', 'invigilators'));
    }

    public function getSeatingCapacity(Request $request)
    {
        $hallId = $request->input('hall_id');
        $courseIds = $request->input('course_ids');

        // Retrieve the selected examination hall
        $hall = ExaminationHall::findOrFail($hallId);

        // Calculate the seating capacity
        $seatingCapacity = $hall->rows * $hall->columns;

        // Perform any other necessary calculations or checks based on the selected courses

        // Prepare the response
        $message = "Seating Capacity: $seatingCapacity";
        // Add any additional messages or checks based on the selected courses

        return response()->json([
            'capacity' => $seatingCapacity,
            'message' => $message,
        ]);
    }

    public function getStudentCount(Request $request)
    {
        $courseIds = $request->input('courses');

        $totalStudentCount = Student::whereIn('id', function ($query) use ($courseIds) {
            $query->select('student_id')
                ->from('course_student')
                ->whereIn('course_id', $courseIds);
        })->count();

        return response()->json(['student_count' => $totalStudentCount]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'courses' => 'required|array',
            'courses.*' => 'exists:courses,id',
            'exam_date' => 'required|date',
            'exam_start_time' => 'required|date_format:H:i',
            'exam_end_time' => 'required|date_format:H:i|after:exam_start_time',
            'examination_hall_id' => 'required|exists:examination_halls,id',
        ]);

        $examinationHall = ExaminationHall::findOrFail($request->examination_hall_id);

        $timetable = ExaminationTimetable::create([
            'exam_date' => $request->exam_date,
            'exam_start_time' => $request->exam_start_time,
            'exam_end_time' => $request->exam_end_time,
            'examination_hall_id' => $request->examination_hall_id,
            'course_code' => Course::whereIn('id', $request->courses)->pluck('code')->implode(','),
            'course_title' => Course::whereIn('id', $request->courses)->pluck('title')->implode(','),
        ]);

        // Retrieve the selected invigilator IDs from the form data
        $invigilatorIds = $request->input('invigilators');

        // Call the allocateTimetableToInvigilators function
        $this->allocateTimetableToInvigilators($invigilatorIds, $timetable->id);


        $courseIds = $request->courses;

        $attachedIds = $timetable->examinationCourses()->sync($courseIds);
        // Redirect or return a response
        return redirect()->route('examination_timetable.index')
            ->with('success', 'Examination timetable added successfully and allocated to invigilator successfully.');
    }


    public function getStudentData($timetableId)
    {
    $attachedIds = DB::table('examination_timetable_course')
        ->where('examination_timetable_id', $timetableId)
        ->pluck('course_id')
        ->toArray();

    $studentData = DB::table('students')
        ->join('course_student', 'students.id', '=', 'course_student.student_id')
        ->join('courses', 'course_student.course_id', '=', 'courses.id')
        ->whereIn('course_student.course_id', $attachedIds)
        ->select('students.id', 'students.name', 'students.matric_number', 'students.department', 'students.phone_number', 'courses.title as course', 'courses.code as course_code')
        ->distinct()
        ->get()
        ->map(function ($item) {
            return (object) $item;
        })
        ->toArray();

    return $studentData;
    }

    protected function adjacencyMatrix($seatLabels, $rows, $columns)
    {
        $numSeats = count($seatLabels);
        $adjacencyMatrix = [];

        for ($i = 0; $i < $numSeats; $i++) {
            for ($j = 0; $j < $numSeats; $j++) {
                // Initialize all matrix entries as 0 (no connections)
                $adjacencyMatrix[$i][$j] = 0;
            }
        }

        // Populate the adjacency matrix based on seat connections
        foreach ($seatLabels as $index => $label) {
            $row = floor($index / $columns); // Calculate the row index
            $col = $index % $columns; // Calculate the column index

            // Check neighboring seats in all four directions (up, down, left, right)
            $neighbors = [
                [$row - 1, $col], // Up
                [$row + 1, $col], // Down
                [$row, $col - 1], // Left
                [$row, $col + 1], // Right
            ];

            foreach ($neighbors as [$neighborRow, $neighborCol]) {
                // Check if the neighbor is within the valid range of rows and columns
                if ($neighborRow >= 0 && $neighborRow < $rows && $neighborCol >= 0 && $neighborCol < $columns) {
                    $neighborIndex = $neighborRow * $columns + $neighborCol;
                    $adjacencyMatrix[$index][$neighborIndex] = 1;
                    $adjacencyMatrix[$neighborIndex][$index] = 1;
                }
            }
        }

        // Increase the matrix size to accommodate the number of colors
        $numColors = 4;
        // dd($numColors);
        for ($i = $numSeats; $i < $numColors; $i++) {
            for ($j = 0; $j < $numColors; $j++) {
                $adjacencyMatrix[$i][$j] = 0;
                $adjacencyMatrix[$j][$i] = 0;
            }
        }

        return $adjacencyMatrix;
    }


    protected function generateSeatingArrangement($timetableId)
    {
    // Assuming you have fetched the examination timetable
    $timetable = ExaminationTimetable::findOrFail($timetableId);
    $examinationHallId = $timetable->examination_hall_id;
    $examinationHall = ExaminationHall::findOrFail($examinationHallId);

    // Get the unique departments associated with the examination timetable
    $courseIds = DB::table('examination_timetable_course')
        ->where('examination_timetable_id', $timetable->id)
        ->pluck('course_id');
    $departments = Course::whereIn('id', $courseIds)->pluck('department')->unique();

    // $departments now contains the unique departments associated with the examination timetable
      
    $rows = $examinationHall->rows; // Number of rows
    $columns = $examinationHall->columns; // Number of columns
    $seatingCapacity = $rows * $columns;

    // Generate labels for each seat
    $seatLabels = [];
    $alphabet = range('A', 'Z');

    for ($row = 1; $row <= $rows; $row++) {
        for ($col = 1; $col <= $columns; $col++) {
            $label = $alphabet[$col - 1] . $row;
            $seatLabels[] = $label;
        }
    }

    // $seatLabels now contains the labels for each seat in the hall

    $studentData = $this->getStudentData($timetableId);
    // dd($studentData);

    $adjacencyMatrix = $this->adjacencyMatrix($seatLabels, $rows, $columns);

    // Create a Graph object
    $graph = new Graph($adjacencyMatrix, $departments);

    // Perform graph coloring
    $graph->welshPowellAlgorithm($departments);

    // Get the assigned colors vector and chromatic number
    $assignedColors = $graph->getColorsVector();
    $chromaticNo = $graph->getChromaticNo();

    $seatingArrangement = [];
    $seatIndex = 0;
    $studentIndex = 0;
    $studentCount = count($studentData);
    
    $seatingArrangementToSave = [];

    foreach ($seatLabels as $seatLabel) {
        // Check if all students have been assigned seats
        if ($studentIndex >= $studentCount) {
            // No more students to assign, set student_id to null
            $studentId = null;
        } else {
            $departmentIndex = $assignedColors[$seatIndex % $seatingCapacity];
            $department = $departments[$departmentIndex];
    
            // Filter students based on their department
            $students = array_filter($studentData, function ($student) use ($department) {
                return $student->department === $department;
            });
    
            if (count($students) > 0) {
                // Assign a student to the seat
                $studentsArray = array_values($students); // Convert associative array to indexed array
                $student = $studentsArray[$studentIndex % count($studentsArray)];
                $studentId = $student->id; // Assign the student ID to the seat
    
                // Create a seating arrangement record
                $hall = ExaminationHall::find($examinationHallId);
                $hallName = $hall ? $hall->name : '';
    
                $seatingArrangementToSave[] = [
                    'examination_hall_id' => $examinationHallId,
                    'hall_name' => $hallName,
                    'seat_number' => $seatLabel,
                    'examination_timetable_id' => $timetableId,
                    'color' => $department,
                    'student_id' => $studentId,
                    'student_name' => $student->name,
                    'course' => $student->course,
                    'course_code' => $student->course_code,
                    'phone_number' => $student->phone_number,
                ];
            }
            
            $studentIndex++;
        }
    
        $seatIndex++;
    }
    // dd($seatingArrangementToSave);
    // Save the seating arrangements with non-null student_id to the database
    if (!empty($seatingArrangementToSave)) {
        DB::table('seating_arrangements')->insert($seatingArrangementToSave);
    }  
        // Redirect to the seating arrangement page
        return redirect()->route('examination_timetable.seating_arrangement', ['timetable' => $timetableId]);
    }


// public function sendSMSNotifications($timetableId)
// {
//     // Fetch the student data and generate the seating arrangement
//     $studentData = $this->getStudentData($timetableId);

//     $seatingArrangements = SeatingArrangement::where('examination_timetable_id', $timetableId)->get();

//     // Get the exam start time and calculate the notification time
//     $timetable = ExaminationTimetable::findOrFail($timetableId);
//     $examStartTime = Carbon::parse($timetable->exam_start_time); // Replace with the actual exam start time
//     $notificationTime = $examStartTime->subMinutes(5);

//     $apiKey = env('TERMII_API_KEY');
//     $senderID = env('SENDER_ID');

//     // Set a counter variable
//     $sentCount = 0;

//     // Loop through the student data and schedule SMS notifications
//     foreach ($seatingArrangements as $seatingArrangement) {
//         // Get the student's seating details from the seating arrangement

//         // Customize the message content based on the student's details
//         $message = "Dear {$seatingArrangement->student_name}, your exam details: Course: {$seatingArrangement->course}, ({$seatingArrangement->course_code}), Hall: {$seatingArrangement->hall_name}, and your Seat Number: {$seatingArrangement->seat_number}. Good luck!";

//         // Send the SMS notification using the Termii API
//         $client = new \GuzzleHttp\Client();

//         $data = [
//             'to' => $seatingArrangement->phone_number,
//             'from' => 'E-Seating', // Replace with your Termii sender ID or phone number
//             'sms' => $message,
//             'api_key' => $apiKey,
//             'channel' => 'generic',
//             'type' => 'plain'
//         ];


//         // Send the SMS notification using the Termii API
//         $response = $client->request('POST', 'https://api.ng.termii.com/api/sms/send', [
//         'headers' => [
//             'Content-Type' => 'application/json',
//             ],
//         'json' => $data,
//         ]);

//         // Check if the SMS was sent successfully
//         if ($response->getStatusCode() === 200) {
//             $sentCount++;
//         }

//         // Check if the desired number of SMS notifications have been sent
//         if ($sentCount >= 1) {
//             break; // Exit the loop
//         }
//     }

//     // Redirect back to the seating arrangement page with a success message
//     return redirect()->back()->with('success', 'Text Messages sent successfully.');
// }

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
        $i = 0;
        foreach ($seatingArrangements as $seatingArrangement) {
            // Check if the SMS notification has already been sent to this phone number
            $existingNotification = SeatingArrangement::where('examination_timetable_id', $timetableId)
                ->where('phone_number', $seatingArrangement->phone_number)
                ->exists();

            // Check if the student has already been processed
            if ($seatingArrangement->processed) {
                continue;
            }

            // Get the student's seating details from the seating arrangement

            // Customize the message content based on the student's details
            $message = "Dear {$seatingArrangement->student_name}, your exam details: Course: {$seatingArrangement->course}, ({$seatingArrangement->course_code}), Hall: {$seatingArrangement->hall_name}, and your Seat Number: {$seatingArrangement->seat_number}. Good luck!";
            
            // dd($message);
        
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

            // Save the sent notification status to the database
            $seatingArrangement->processed = true;
            $seatingArrangement->message = $message;
            if ($response->getStatusCode() === 200) {
                $seatingArrangement->delivery_status = 'delivered';
                $seatingArrangement->save();
            } else{
                $seatingArrangement->delivery_status = 'failed';
                $seatingArrangement->save();
            }
        
            // $i++;
            // if ($i >= 3) {
            //     break;
            // }
        }


        // Redirect back to the seating arrangement page with a success message
        return redirect()->back()->with('success', 'Text Messages sent successfully.');
    }

 


    public function seatingArrangement(ExaminationTimetable $timetable)
    {
        // Retrieve the seating arrangements for the specified timetable
        $seatingArrangements = SeatingArrangement::where('examination_timetable_id', $timetable->id)->get();
        $examinationHallIds = $seatingArrangements->pluck('examination_hall_id')->unique();
        $examinationHalls = ExaminationHall::whereIn('id', $examinationHallIds)->get();

        $invigilators = User::where('examination_timetable_id', $timetable->id)->get();


        // Fetch the number of rows and columns for each examination hall
        $hallRows = [];
        $hallColumns = [];

        foreach ($examinationHalls as $hall) {
            $hallRows[$hall->id] = $hall->rows;
            $hallColumns[$hall->id] = $hall->columns;
        }
        // Create a seating graph array with seat numbers as keys and student details as values
        $seatingGraph = [];

        foreach ($seatingArrangements as $seatingArrangement) {
            $seatNumber = $seatingArrangement->seat_number;
            $studentName = $seatingArrangement->student_name;
            $studentMatric = $seatingArrangement->student->matric_number;
            $studentDepartment = $seatingArrangement->color;
            $courseCode = $seatingArrangement->course_code;
            $courseTitle = $seatingArrangement->course;
            $examinationHallId = $seatingArrangement->examination_hall_id; // Add this line

            // Split the seat label into row and column components
            $row = substr($seatNumber, 1);
            $column = ord($seatNumber[0]) - ord('A') + 1;


            $seatingGraph[] = [
                'seat_number' => $seatNumber,
                'student' => $studentName,
                'matric' => $studentMatric,
                'department' => $studentDepartment,
                'course_code' => $courseCode,
                'course_title' => $courseTitle,
                'examination_hall_id' => $examinationHallId, // Add this line
                'row' => $row,
                'column' => $column,
            ];
        }

        // dd($hallRows, $hallColumns);

        // Pass the seating arrangements, examination halls, and timetable to the view
        return view('examination_timetable.seating_arrangement', compact('seatingArrangements', 'examinationHalls', 'timetable', 'seatingGraph', 'hallRows', 'hallColumns', 'invigilators'));
    }

    

    public function allocateTimetableToInvigilators($invigilatorIds, $timetableId)
    {
        $timetable = ExaminationTimetable::findOrFail($timetableId);

        // Update the timetable_id for each invigilator in the users table
        foreach ($invigilatorIds as $invigilatorId) {
            $invigilator = User::findOrFail($invigilatorId);
            $invigilator->examination_timetable_id = $timetable->id;
            $invigilator->save();
        }

        // Update the invigilator relationship for the timetable in the examination_timetable table
        $timetable->invigilators()->sync($invigilatorIds);

        // Additional logic if needed

        return redirect()->back()->with('success', 'Timetable allocated to invigilators successfully.');
    }


    public function show($id)
    {
        $examination_timetable = ExaminationTimetable::find($id);
        return view('examination_timetable.show', compact('examination_timetable'));
    }

    public function edit($id)
    {
        $examination_timetable = ExaminationTimetable::findOrFail($id);
        $halls = ExaminationHall::all();
        return view('examination_timetable.edit', compact('examination_timetable', 'halls'));
    }


    public function update(Request $request, ExaminationTimetable $examinationTimetable)
    {
        $request->validate([
            'course_code' => 'required',
            'course_title' => 'required',
            'exam_date' => 'required|date',
            'exam_start_time' => 'required',
            'exam_end_time' => 'required',
            'examination_hall_id' => 'required|exists:examination_halls,id'
        ]);

        $examinationTimetable->course_code = $request->course_code;
        $examinationTimetable->course_title = $request->course_title;
        $examinationTimetable->exam_date = $request->exam_date;
        $examinationTimetable->exam_start_time = $request->exam_start_time;
        $examinationTimetable->exam_end_time = $request->exam_end_time;
        $examinationTimetable->examination_hall_id = $request->examination_hall_id;

        $examinationTimetable->save();

        return redirect()->route('examination_timetable.index')
            ->with('success', 'Examination timetable updated successfully.');
    }

    public function destroy($id)
    {
        $examination_timetable = ExaminationTimetable::find($id);
        if ($examination_timetable) {
            $examination_timetable->delete();
            return redirect()->route('examination_timetable.index')->with('status', 'Examination timetable deleted successfully.');
        } else {
            return redirect()->route('examination_timetable.index')->with('status', 'Examination timetable not found.');
        }
    }

    public function getStudentList($timetableId)
    {
        // Retrieve the seating arrangements for the specified timetable
        $seatingArrangements = SeatingArrangement::where('examination_timetable_id', $timetableId)->get();
        $examinationHallIds = $seatingArrangements->pluck('examination_hall_id')->unique();
        $examinationHalls = ExaminationHall::whereIn('id', $examinationHallIds)->get();   


        return view('examination_timetable.student_list', compact('seatingArrangements'));
    }

    public function downloadSeatingArrangementPDF(ExaminationTimetable $timetable)
    {
         // Retrieve the seating arrangements for the specified timetable
         $seatingArrangements = SeatingArrangement::where('examination_timetable_id', $timetable->id)->get();
         $examinationHallIds = $seatingArrangements->pluck('examination_hall_id')->unique();
         $examinationHalls = ExaminationHall::whereIn('id', $examinationHallIds)->get();
 
         // Fetch the number of rows and columns for each examination hall
         $hallRows = [];
         $hallColumns = [];
        
         $invigilators = User::where('examination_timetable_id', $timetable->id)->get();

         foreach ($examinationHalls as $hall) {
             $hallRows[$hall->id] = $hall->rows;
             $hallColumns[$hall->id] = $hall->columns;
         }
         // Create a seating graph array with seat numbers as keys and student details as values
         $seatingGraph = [];
 
         foreach ($seatingArrangements as $seatingArrangement) {
            $seatNumber = $seatingArrangement->seat_number;
             $studentName = $seatingArrangement->student_name;
             $studentMatric = $seatingArrangement->student->matric_number;
             $studentDepartment = $seatingArrangement->color;
             $courseCode = $seatingArrangement->course_code;
             $courseTitle = $seatingArrangement->course;
             $examinationHallId = $seatingArrangement->examination_hall_id; // Add this line
 
             // Split the seat label into row and column components
             $row = substr($seatNumber, 1);
             $column = ord($seatNumber[0]) - ord('A') + 1;
 
 
             $seatingGraph[] = [
                 'seat_number' => $seatNumber,
                 'student' => $studentName,
                 'matric' => $studentMatric,
                 'department' => $studentDepartment,
                 'course_code' => $courseCode,
                 'course_title' => $courseTitle,
                 'examination_hall_id' => $examinationHallId, // Add this line
                 'row' => $row,
                 'column' => $column,
             ];
         }
 

        // Generate the HTML from the view
        $html = view('examination_timetable.seating_arrangement', compact('seatingArrangements', 'examinationHalls', 'timetable', 'seatingGraph', 'hallRows', 'hallColumns', 'invigilators'))->render();
    
        // Instantiate the Dompdf class
        $dompdf = new Dompdf();
    
        // Load the HTML content into Dompdf
        $dompdf->loadHtml($html);
    
        // (Optional) Customize the PDF settings
        $dompdf->setPaper('A2', 'landscape');
    
        // Render the PDF
        $dompdf->render();
    
        // Output the PDF for download
        return $dompdf->stream('seating_arrangement.pdf');
    }


    

}
