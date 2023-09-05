<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExaminationTimetableUserTable extends Migration
{
    public function up()
    {
        Schema::create('examination_timetable_user', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('examination_timetable_id');
            $table->unsignedBigInteger('user_id');
            $table->timestamps();

            $table->foreign('examination_timetable_id')->references('id')->on('examination_timetable')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('examination_timetable_user');
    }
}
