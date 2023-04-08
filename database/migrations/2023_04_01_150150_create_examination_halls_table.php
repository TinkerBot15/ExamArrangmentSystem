<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExaminationHallsTable extends Migration
{
    public function up()
    {
        Schema::create('examination_halls', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('seating_capacity');
            $table->integer('rows');
            $table->integer('columns');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('examination_halls');
    }
}

