<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Seeders\CoursesTableSeeder;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(StudentsTableSeeder::class);
        $this->call(CoursesTableSeeder::class);
    }
}
