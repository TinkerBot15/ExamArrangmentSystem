<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class StudentsTableSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        // Generate 50 students with realistic data
        for ($i = 0; $i < 500; $i++) {
            DB::table('students')->insert([
                'name' => $faker->name,
                'department' => $faker->randomElement(['Computer Engineering', 'Electrical Engineering', 'Mechanical Engineering', 'Systems Engineering']),
                'matric_number' => $faker->unique()->numberBetween(160401001, 160408090),
                'phone_number' => $faker->phoneNumber,
                'email' => $faker->unique()->safeEmail,
                'created_at' => $faker->dateTimeBetween('-1 year', 'now'),
                'updated_at' => now(),
            ]);
        }
    }
}
