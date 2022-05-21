<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTripBusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trip_buses', function (Blueprint $table) {
            $table->foreignId('bus_id')->references('id')->on('buses')
                ->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('trip_id')->references('id')->on('trips')
                ->onDelete('cascade')->onUpdate('cascade');
            $table->primary(['bus_id', 'trip_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('trip_buses');
    }
}
