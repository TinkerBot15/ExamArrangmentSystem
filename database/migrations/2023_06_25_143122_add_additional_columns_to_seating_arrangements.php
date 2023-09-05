<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAdditionalColumnsToSeatingArrangements extends Migration
{
    public function up()
    {
        Schema::table('seating_arrangements', function (Blueprint $table) {
            $table->string('hall_name')->nullable();
            $table->string('course')->nullable();
            $table->string('course_code')->nullable();
        });
    }

    public function down()
    {
        Schema::table('seating_arrangements', function (Blueprint $table) {
            $table->dropColumn('hall_name');
            $table->dropColumn('course');
            $table->dropColumn('course_code');
        });
    }
}

