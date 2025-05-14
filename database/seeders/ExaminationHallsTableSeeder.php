<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class ExaminationHallsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        $rows = $faker->numberBetween(10, 15);
        $columns = $faker->numberBetween(50, 60);

        for ($i = 1; $i <= 10; $i++) {
            DB::table('examination_halls')->insert([
                'name' => 'Room ' . $i,
                'rows' => $rows,
                'columns' => $columns,
                'seating_capacity' => $rows * $columns,
                'created_at' => $faker->dateTimeBetween('-1 year', 'now'),
                'updated_at' => now(),
            ]);
        }
    }
}