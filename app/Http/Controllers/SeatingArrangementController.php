<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SeatingArrangementController extends Controller
{
    
            public function arrangeStudents($students, $courses, $examinationHalls)
        {
            // Create a graph where each vertex represents a student
            // and edges connect vertices representing students who are
            // offering the same course
            $graph = collect($students)->reduce(function ($carry, $student) use ($courses) {
                $coursesForStudent = $courses->where('student_id', $student->id)->pluck('course_code');
                $carry[$student->id] = $coursesForStudent->flatMap(function ($course) use ($students, $student) {
                    return $students->where('id', '!=', $student->id)->whereHas('courses', function ($query) use ($course) {
                        $query->where('course_code', $course);
                    })->pluck('id')->toArray();
                })->toArray();
                return $carry;
            }, []);

            // Use graph coloring algorithm to assign seats to students
            $seatingArrangements = collect($examinationHalls)->map(function ($hall) use ($graph, $students) {
                $colors = $this->graphColoring($graph, $hall['capacity']);
                $seatingArrangement = collect($colors)->reduce(function ($carry, $color) use ($students) {
                    $studentsForColor = $students->whereIn('id', $color);
                    $carry[] = $studentsForColor->map(function ($student) {
                        return [
                            'student_id' => $student->id,
                            'name' => $student->name,
                            'course_codes' => $student->courses->pluck('course_code')->toArray()
                        ];
                    })->toArray();
                    return $carry;
                }, []);
                return [
                    'hall_id' => $hall['id'],
                    'seating_arrangement' => $seatingArrangement
                ];
            });

            return $seatingArrangements;
        }

        private function graphColoring($graph, $numColors)
        {
            $colors = [];
            $this->graphColoringHelper($graph, $numColors, $colors, 0);
            return $colors;
        }

        private function graphColoringHelper($graph, $numColors, &$colors, $vertex)
        {
            if ($vertex == count($graph)) {
                return true;
            }

            for ($color = 1; $color <= $numColors; $color++) {
                if ($this->isSafe($graph, $vertex, $colors, $color)) {
                    $colors[$vertex] = $color;
                    if ($this->graphColoringHelper($graph, $numColors, $colors, $vertex + 1)) {
                        return true;
                    }
                    $colors[$vertex] = 0;
                }
            }

            return false;
        }

    
        public function getSeatingArrangement($exam_hall_id)
        {
            $students = DB::table('students')
                ->select('students.id', 'students.firstname', 'students.lastname', 'seating_arrangements.seat_number')
                ->join('seating_arrangements', 'students.id', '=', 'seating_arrangements.student_id')
                ->where('seating_arrangements.exam_hall_id', '=', $exam_hall_id)
                ->orderBy('seating_arrangements.seat_number')
                ->get();

            $exam_hall = DB::table('examination_halls')->where('id', '=', $exam_hall_id)->first();

            $pdf = \PDF::loadView('pdf.seating-arrangement', ['students' => $students, 'exam_hall' => $exam_hall]);
            $pdf_name = Str::random(10) . '.pdf';

            Storage::disk('public')->put('seating-arrangements/' . $pdf_name, $pdf->output());

            return view('seating-arrangement', ['students' => $students, 'exam_hall' => $exam_hall, 'pdf_name' => $pdf_name]);
        }
}


