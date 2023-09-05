<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CoursesTableSeeder extends Seeder
{
    public function run()
    {
        $courses = [
            [
                'name' => 'Systems Programming',
                'code' => 'CPE523',
                'department' => 'Computer Engineering'
            ],
            [
                'name' => 'Microprogramming and Data Structures',
                'code' => 'CPE544',
                'department' => 'Computer Engineering'
            ],
            [
                'name' => 'Electrical Machines',
                'code' => 'EEG522',
                'department' => 'Electrical Engineering'
            ],
            [
                'name' => 'Control Systems and Synthesis',
                'code' => 'EEG563',
                'department' => 'Electrical Engineering'
            ],
            [
                'name' => 'Mechanics of Materials',
                'code' => 'MEG522',
                'department' => 'Mechanical Engineering'
            ],
            [
                'name' => 'Advanced Thermodynamics',
                'code' => 'MEG534',
                'department' => 'Mechanical Engineering'
            ],
            [
                'name' => 'Systems Analysis and Design',
                'code' => 'SSG511',
                'department' => 'Systems Engineering'
            ],
            [
                'name' => 'Artificial Intelligence',
                'code' => 'SSG531',
                'department' => 'Systems Engineering'
            ]
        ];

        foreach ($courses as $course) {
            DB::table('courses')->insert([
                'name' => $course['name'],
                'code' => $course['code'],
                'department' => $course['department'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
