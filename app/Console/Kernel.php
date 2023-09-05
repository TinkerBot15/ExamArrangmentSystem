<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Carbon\Carbon;
use App\Models\ExaminationTimetable;
use App\Services\TwilioService;
use App\Models\SeatingArrangement;
use App\Console\Commands\MapStudentsToCoursesCommand;


class Kernel extends ConsoleKernel
{

    protected $commands = [
        MapStudentsToCoursesCommand::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->call(function () {
            // Fetch the timetable ID from the sendSMSNotifications function in the controller
            $controller = app('App\Http\Controllers\ExamTimetableController');
            $timetableId = $controller->sendSMSNotifications($request);;

            // Get the current date and time
            $now = Carbon::now();

            // Fetch the exam timetable for the current date
            $examTimetable = ExaminationTimetable::findOrFail($timetableId);

            if ($examTimetable) {
                // Calculate the exam start time and notification time
                $examStartTime = Carbon::parse($examTimetable->exam_start_time);
                $notificationTime = $examStartTime->subMinutes(5);

                // Fetch the student data and generate the seating arrangement
                $studentData = $this->getStudentData($examTimetable->id);
                $seatingArrangement = $this->generateSeatingArrangement($studentData);

                // Initialize the TwilioService
                $twilioService = new TwilioService();

                foreach ($studentData as $student) {
                    // Get the student's seating details from the seating arrangement
                    $hallId = $seatingArrangement[$student->id]['examination_hall_id'];
                    $hall = ExaminationHall::find($hallId)->name;

                    // Customize the message content based on the student's details
                    $message = "Dear {$student->name}, your exam details: Course {$student->course} ({$student->course_code}), Hall {$hall}, Seat {$student->seat_number}. Good luck!";

                    // Schedule the SMS notification at the specified time
                    $twilioService->scheduleSMS($student->phone_number, $message, $notificationTime);
                }
            }
        })->everyMinute(); // Run the scheduler every minute

        // ... other scheduled commands or jobs ...
    }

    // ... other methods ...


    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
