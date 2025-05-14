<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddExaminationTimetableIdToSeatingArrangementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('seating_arrangements', function (Blueprint $table) {
            $table->unsignedBigInteger('examination_timetable_id')->nullable();

            $table->foreign('examination_timetable_id')
                ->references('id')->on('examination_timetable')
                ->onDelete('cascade');
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('seating_arrangements', function (Blueprint $table) {
            //
        });
    }
}
