<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


use App\Models\CourseStudent;
use App\Models\Course;
use App\Models\Student;

class CourseStudentController extends Controller
{
    public function mapStudentsToCoursesWithSameDepartment()
    {
        // Retrieve all courses
        $courses = Course::all();

        foreach ($courses as $course) {
            $students = Student::where('department', $course->department)->get();

            foreach ($students as $student) {
                // Create an association between the student and the course
                $courseStudent = new CourseStudent();
                $courseStudent->course_id = $course->id;
                $courseStudent->student_id = $student->id;
                $courseStudent->save();
            }
        }

        // Return a response or perform any additional actions
    }
    // Other methods for retrieving, updating, or deleting associations
}
