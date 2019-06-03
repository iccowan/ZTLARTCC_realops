<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class LiveRwFlights extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rw_flights', function(Blueprint $table) {
           $table->increments('id');
           $table->string('code')->nullable();
           $table->string('depicao')->nullable();
           $table->string('arricao')->nullable();
           $table->text('route')->nullable();
           $table->string('tailnum')->nullable();
           $table->string('flightlevel')->nullable();
           $table->string('deptime')->nullable();
           $table->string('arrtime')->nullable();
           $table->string('flighttime')->nullable();
           $table->string('daysofweek')->nullable();
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
        Schema::dropIfExists('rw_flights');
    }
}
