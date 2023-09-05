<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\Student;
use App\Models\CourseStudent;


use App\Services\TwilioService;

use Carbon\Carbon;

class ExamController extends Controller
{
    protected $twilioService;

    public function __construct(TwilioService $twilioService)
    {
        $this->twilioService = $twilioService;
    }

    public function notifyStudent($student)
    {
        // Retrieve the student's phone number, exam hall, seat number, and exam time
        $phone = $student->phone_number;
        $examHall = $student->exam_hall;
        $seatNumber = $student->seat_number;
        $examTime = Carbon::parse($student->exam_time);

        // Calculate the notification time (10 minutes before the exam time)
        $notificationTime = $examTime->subMinutes(10);

        // Get the current time
        $currentTime = Carbon::now();

        // Check if the current time is within the notification time window
        if ($currentTime >= $notificationTime && $currentTime < $examTime) {
            // Compose the message
            $message = "Hello, {$student->name}! Your exam is scheduled at {$examTime}. ";
            $message .= "Please proceed to Exam Hall {$examHall} and take seat number {$seatNumber}.";

            // Send the SMS notification
            $this->twilioService->sendSMS($phone, $message);
        }
    }
}

