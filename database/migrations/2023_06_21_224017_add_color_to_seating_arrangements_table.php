<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColorToSeatingArrangementsTable extends Migration
{
    public function up()
    {
        Schema::table('seating_arrangements', function (Blueprint $table) {
            $table->string('color', 50)->nullable();
        });
    }

    public function down()
    {
        Schema::table('seating_arrangements', function (Blueprint $table) {
            $table->dropColumn('color');
        });
    }
}

