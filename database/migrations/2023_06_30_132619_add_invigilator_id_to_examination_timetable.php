<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddInvigilatorIdToExaminationTimetable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('examination_timetable', function (Blueprint $table) {
            $table->unsignedBigInteger('invigilator_id')->nullable();
            $table->foreign('invigilator_id')->references('id')->on('users');
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('examination_timetable', function (Blueprint $table) {
            //
        });
    }
}
