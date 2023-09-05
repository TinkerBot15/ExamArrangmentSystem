<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Course;
use App\Models\Student;
use App\Models\CourseStudent;

class MapStudentsToCoursesCommand extends Command
{
    protected $signature = 'map:students-courses';
    protected $description = 'Maps students to courses with the same department';

    public function handle()
    {
        $courses = Course::all();

        foreach ($courses as $course) {
            $students = Student::where('department', $course->department)->get();

            foreach ($students as $student) {
                $courseStudent = new CourseStudent();
                $courseStudent->course_id = $course->id;
                $courseStudent->student_id = $student->id;
                $courseStudent->save();
            }
        }

        $this->info('Mapping completed!');
    }
}
