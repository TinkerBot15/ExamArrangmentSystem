<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSeatingArrangementsTable extends Migration
{
    public function up()
    {
        Schema::create('seating_arrangements', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id');
            $table->unsignedBigInteger('examination_hall_id');
            $table->unsignedInteger('seat_number');
            $table->string('phone_number')->nullable();
            $table->timestamps();

            $table->foreign('student_id')
                ->references('id')->on('students')
                ->onDelete('cascade');

            $table->foreign('examination_hall_id')
                ->references('id')->on('examination_halls')
                ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('seating_arrangements');
    }
}
