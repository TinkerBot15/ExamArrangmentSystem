

use Nexmo\Client as NexmoClient;
use Nexmo\Client\Credentials\Basic as NexmoCredentials;

public function sendSMSNotifications($timetableId)
{
    // Fetch the student data and generate the seating arrangement
    $studentData = $this->getStudentData($timetableId);
    
    $seatingArrangements = SeatingArrangement::where('examination_timetable_id', $timetableId)->get();

    // Get the exam start time and calculate the notification time
    $timetable = ExaminationTimetable::findOrFail($timetableId);
    $examStartTime = Carbon::parse($timetable->exam_start_time); // Replace with the actual exam start time
    $notificationTime = $examStartTime->subMinutes(5);

    $nexmoApiKey = env('NEXMO_API_KEY');
    $nexmoApiSecret = env('NEXMO_API_SECRET');
    $nexmoPhoneNumber = env('NEXMO_PHONE_NUMBER');
    // Initialize the Nexmo client
    $nexmoClient = new NexmoClient(new NexmoCredentials($nexmoApiKey, $nexmoApiSecret));

    // dd($nexmoApiKey, $nexmoApiSecret, $nexmoPhoneNumber, $nexmoClient);
    // Set a counter variable
    $sentCount = 0;

    // Loop through the student data and schedule SMS notifications
    foreach ($seatingArrangements as $seatingArrangement) {
        // Get the student's seating details from the seating arrangement

        // Customize the message content based on the student's details
        $message = "Dear {$seatingArrangement->student_name}, your exam details: Course: {$seatingArrangement->course}, ($seatingArrangement->course_code), Hall: {$seatingArrangement->hall_name}, and your Seat Number: {$seatingArrangement->seat_number}. Good luck!";

        // Schedule the SMS notification at the specified time
        $nexmoClient->message()->send([
            'to' => $seatingArrangement->phone_number,
            'from' => $nexmoPhoneNumber,
            'text' => $message,
            'type' => 'unicode'
        ]);
        

        // Increment the counter
        $sentCount++;

        // Check if the desired number of SMS notifications have been sent
        if ($sentCount >= 1) {
            break; // Exit the loop
        }
    }

    // Redirect back to the seating arrangement page with a success message
    return redirect()->back()->with('success', 'Text Messages sent successfully.'); 
}


