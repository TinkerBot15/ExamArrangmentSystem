<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterSeatingArrangementsTable extends Migration
{
    public function up()
    {
        Schema::table('seating_arrangements', function (Blueprint $table) {
            $table->string('seat_number')->change(); // Change the data type to VARCHAR or CHAR
        });
    }

    public function down()
    {
        Schema::table('seating_arrangements', function (Blueprint $table) {
            $table->integer('seat_number')->change(); // Revert the data type back to integer if needed
        });
    }
}
