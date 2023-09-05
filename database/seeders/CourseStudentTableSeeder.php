<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class CourseStudentTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // get all courses and students
        $courses = DB::table('courses')->get();
        $students = DB::table('students')->get();
        
        // loop through each course and assign a random set of students
        foreach ($courses as $course) {
            // get a random subset of students for the course
            $num_students = rand(10, 50);
            $course_students = $students->random($num_students);
            
            // insert records into course_student table
            foreach ($course_students as $student) {
                DB::table('course_student')->insert([
                    'course_id' => $course->id,
                    'student_id' => $student->id,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
        }
    }
}