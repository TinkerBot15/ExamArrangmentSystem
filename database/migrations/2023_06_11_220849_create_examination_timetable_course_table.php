<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExaminationTimetableCourseTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('examination_timetable_course', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('examination_timetable_id');
            $table->unsignedBigInteger('course_id');
            $table->timestamps();

            $table->foreign('examination_timetable_id')->references('id')->on('examination_timetable')->onDelete('cascade');
            $table->foreign('course_id')->references('id')->on('courses')->onDelete('cascade');
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('examination_timetable_course');
    }
}
