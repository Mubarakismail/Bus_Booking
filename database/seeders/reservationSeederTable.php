<?php

namespace Database\Seeders;

use App\Models\Reservation;
use App\Models\Seat;
use App\Models\Station;
use App\Models\Trip;
use App\Models\User;
use Faker\Factory;
use Illuminate\Database\Seeder;

class reservationSeederTable extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker=Factory::create();
        $stations = Station::all()->pluck('id')->toArray();
        $trips = Trip::all()->pluck('id')->toArray();
        $seats = Seat::all()->pluck('id')->toArray();
        $users = User::all()->pluck('id')->toArray();
        for ($i=0; $i < rand(10,50); $i++) { 
            $tripId=$faker->randomElement($trips);
            $trip=Trip::find($tripId)->first();
            $trip_station=$trip->stations;
            Reservation::create([
                'trip_id'=>$tripId,
                'user_id'=>$faker->randomElement($users),
                'start_station_id'=>$faker->randomElement($stations),
                'end_station_id'=>$faker->randomElement($trips),
                'seat_id'=>$faker->randomElement($trips),
            ]);
        }
    }
}
