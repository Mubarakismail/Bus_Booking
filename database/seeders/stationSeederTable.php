<?php

namespace Database\Seeders;

use App\Models\Station;
use Illuminate\Database\Seeder;

class stationSeederTable extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $stations = ['Alexandria', 'Port Said', 'Tanta', 'Mansoura', 'Cairo', 'Fayoum', 'Minya', 'Assuit', 'Luxor'];
        for ($i=0; $i < sizeof($stations); $i++) { 
            Station::create([
                'station_name'=>$stations[$i],
            ]);
        }
    }
}
