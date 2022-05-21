<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTripStationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trip_stations', function (Blueprint $table) {
            $table->foreignId('station_id')->references('id')->on('stations')
                ->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('trip_id')->references('id')->on('trips')
                ->onDelete('cascade')->onUpdate('cascade');
            $table->integer('stop_number');
            $table->primary(['station_id', 'trip_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('trip_stations');
    }
}
