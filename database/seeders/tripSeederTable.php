<?php

namespace Database\Seeders;

use App\Models\Bus;
use App\Models\Station;
use App\Models\Trip;
use Illuminate\Database\Seeder;

class tripSeederTable extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Trip::factory(rand(10, 20))->create()->each(function ($trip) {
            $trip->buses()->saveMany(Bus::factory(rand(0, 4))->make());
            $stations = Station::all()->random(rand(0, 4))->pluck('id')->toArray();
            $idx = 0;
            foreach ($stations as $station) {
                ++$idx;
                \DB::table('trip_stations')->insert([
                    'trip_id' => $trip->id,
                    'station_id' => $station,
                    'stop_number' => $idx,
                ]);
            }
        });
    }
}
