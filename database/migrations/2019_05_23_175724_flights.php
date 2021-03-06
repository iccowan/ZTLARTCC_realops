<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Flights extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('flights', function(Blueprint $table) {
            $table->increments('id');
            $table->string('callsign');
            $table->string('departure');
            $table->string('arrival');
            $table->text('flight_plan');
            $table->dateTime('dep_time');
            $table->dateTime('arr_time');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('flights');
    }
}
