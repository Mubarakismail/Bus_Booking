<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReservationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            // foreign keys
            $table->foreignId('trip_id')->references('id')->on('trips')
                ->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('seat_id')->references('id')->on('seats')
                ->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('start_station_id')->references('id')->on('stations')
                ->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('end_station_id')->references('id')->on('stations')
                ->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('user_id')->references('id')->on('users')
                ->onDelete('cascade')->onUpdate('cascade');
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
        Schema::dropIfExists('reservations');
    }
}
