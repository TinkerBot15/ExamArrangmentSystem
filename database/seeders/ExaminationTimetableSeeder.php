<?php

namespace Database\Seeders;

use Exception;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;


class ExaminationTimetableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        
        // get all examination halls
        $halls = DB::table('examination_halls')->get();
        
        // get all courses
        $courses = DB::table('courses')->get();
        
        // sort courses by number of students in descending order
        $sortedCourses = $courses->sortByDesc(function ($course) {
            return DB::table('course_student')->where('course_id', $course->id)->count();
        })->values();
        
        // initialize array to store exam timetable
        $timetable = [];
        
        // loop through sorted courses and assign to hall(s)
        foreach ($sortedCourses as $course) {
            // find hall(s) that can accommodate the course
            $matchingHalls = $halls->filter(function ($hall) use ($course, $timetable) {
                // check if hall is already being used at the same time
                foreach ($timetable as $exam) {
                    if ($exam->examination_hall_id == $hall->id &&
                        $exam->exam_date == Faker::create()->dateTimeBetween('next week', '+2 weeks') &&
                        $exam->exam_start_time == Faker::create()->dateTimeBetween('next week', '+2 weeks')
                    ) {
                        return false;
                    }
                }
                
                return $hall->seating_capacity >= DB::table('course_student')->where('course_id', $course->id)->count();
            });
            
            // if no hall is available, skip the course
            if ($matchingHalls->isEmpty()) {
                continue;
            }
            
            // select a random hall from the matching halls
            $hall = $matchingHalls->random();
            
            // add course to exam timetable
            $timetable[] = (object) [
                'course_code' => $course->code,
                'course_title' => $course->name,
                'exam_date' => $faker->dateTimeBetween('next week', '+2 weeks'),
                'exam_start_time' => $faker->dateTimeBetween('next week', '+2 weeks'),
                'exam_end_time' => $faker->dateTimeBetween('+3 weeks', '+4 weeks'),
                'examination_hall_id' => $hall->id,
                'created_at' => $faker->dateTimeBetween('-1 year', 'now'),
                'updated_at' => now(),
            ];
        }
        
        // insert exam timetable into database
        DB::table('examination_timetable')->insert($timetable);
    }
}
