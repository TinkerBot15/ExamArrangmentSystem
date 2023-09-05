<?php

namespace App\Services;

use App\Models\Student;
use Twilio\Rest\Client;

class ExaminationNotificationService
{
    protected $twilioClient;

    public function __construct()
    {
        $accountSid = env('TWILIO_SID');
        $authToken = env('TWILIO_AUTH_TOKEN');

        // Create Twilio client
        $this->twilioClient = new Client($accountSid, $authToken);
    }

    public function sendExamNotifications($timetableId, $examStartTime)
    {
        // Retrieve the student data for the given timetable ID
        $studentData = $this->getStudentData($timetableId);

        // Calculate the time to send the SMS messages (10 minutes before exam start time)
        $timeToSend = strtotime('-10 minutes', strtotime($examStartTime));

        // Loop through the student data and send SMS messages
        foreach ($studentData as $student) {
            $studentPhoneNumber = $student->phone_number;
            $examHall = $student->exam_hall;
            $seatNumber = $student->seat_number;

            // Prepare the message content with the student's exam details
            $messageContent = "Dear student, your exam details:\nExam Hall: $examHall\nSeat Number: $seatNumber";

            // Check if it's time to send the SMS message
            if (time() >= $timeToSend) {
                // Send the SMS message
                $this->sendSMS($studentPhoneNumber, $messageContent);
            }
        }
    }

    protected function getStudentData($timetableId)
    {
        // Retrieve the student data based on the timetable ID
        return Student::join('seating_arrangement', 'students.id', '=', 'seating_arrangement.student_id')
            ->where('seating_arrangement.examination_timetable_id', $timetableId)
            ->select('students.phone_number', 'seating_arrangement.exam_hall', 'seating_arrangement.seat_number')
            ->get();
    }

    protected function sendSMS($to, $message)
    {
        $twilioPhoneNumber = env('TWILIO_PHONE_NUMBER');

        // Send SMS using Twilio client
        $message = $this->twilioClient->messages->create(
            $to,
            [
                'from' => $twilioPhoneNumber,
                'body' => $message,
            ]
        );

        return $message->sid;
    }

    // Add more methods as needed for Twilio integration

}
