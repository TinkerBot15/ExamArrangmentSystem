<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExaminationHallExaminationTimetableTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('examination_hall_examination_timetable', function (Blueprint $table) {
            $table->unsignedBigInteger('examination_hall_id');
            $table->unsignedBigInteger('examination_timetable_id');
            $table->timestamps();
            
            $table->foreign('examination_hall_id')->references('id')->on('examination_halls')->onDelete('cascade')->name('eh_et_eh_id_fk');
            $table->foreign('examination_timetable_id')->references('id')->on('examination_timetable')->onDelete('cascade')->name('eh_et_et_id_fk');
            
            $table->primary(['examination_hall_id', 'examination_timetable_id'])->name('eh_et_primary');
        });
        
        
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('examination_hall_examination_timetable');
    }
}
