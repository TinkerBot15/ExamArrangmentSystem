<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExaminationTimetableTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('examination_timetable', function (Blueprint $table) {
            $table->id();
            $table->string('course_code');
            $table->string('course_title');
            $table->date('exam_date');
            $table->time('exam_start_time');
            $table->time('exam_end_time');
            $table->unsignedBigInteger('examination_hall_id');
            $table->timestamps();

            // Add foreign key constraint for examination_hall_id
            $table->foreign('examination_hall_id')->references('id')->on('examination_halls')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('examination_timetable');
    }
}
