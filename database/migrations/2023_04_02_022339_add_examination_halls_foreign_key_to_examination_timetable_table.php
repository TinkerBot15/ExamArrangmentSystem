<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddExaminationHallsForeignKeyToExaminationTimetableTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('examination_timetable', function (Blueprint $table) {
    $table->dropForeign('new_foreign_key_constraint_name');
    $table->foreign('examination_hall_id')->references('id')->on('examination_halls')->onDelete('cascade')->unique('dnew_foreign_key_constraint_name');
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
           $table->dropForeign(['examination_hall_id']);
        });
    }
}
