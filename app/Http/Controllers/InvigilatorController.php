<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
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

class InvigilatorController extends Controller
{
    public function index()
    {
        // Check if the authenticated user has the invigilator role
        if (!Gate::allows('invigilator-access')) {
            abort(403); // Unauthorized access
        }
    
        $invigilatorId = Auth::user()->id;

        // $attachedIds = DB::table('examination_timetable_course')
        // ->where('examination_timetable_id', $timetableId)
        // ->pluck('course_id')
        // ->toArray();

        $examination_timetableIds = DB::table('examination_timetable_user')
        ->where('user_id', $invigilatorId)
        ->pluck('examination_timetable_id')->toArray();

        $examination_timetables = ExaminationTimetable::where('id', $examination_timetableIds)->get();
        
    
        //  = ExaminationTimetable::whereHas('invigilators', function ($query) use ($invigilatorId) {
        //     $query->where('users.id', $invigilatorId);
        // })->get();
    
        return view('examination_timetable.invigilator.index', compact('examination_timetables'));
    }
    

    public function show(ExaminationTimetable $timetable)
    {
        $examination_timetable = ExaminationTimetable::find($timetable->id);
        return view('examination_timetable.invigilator.show', compact('examination_timetable'));
    }

    public function seatingArrangement(ExaminationTimetable $timetable)
    {
         // Retrieve the seating arrangements for the specified timetable
         $seatingArrangements = SeatingArrangement::where('examination_timetable_id', $timetable->id)->get();
         $examinationHallIds = $seatingArrangements->pluck('examination_hall_id')->unique();
         $examinationHalls = ExaminationHall::whereIn('id', $examinationHallIds)->get();
 

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
       return view('examination_timetable.invigilator.seating_arrangement', compact('seatingArrangements', 'examinationHalls', 'timetable', 'seatingGraph', 'hallRows', 'hallColumns'));
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
        $html = view('examination_timetable.invigilator.seating_arrangement', compact('seatingArrangements', 'examinationHalls', 'timetable', 'seatingGraph', 'hallRows', 'hallColumns'))->render();
    
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
