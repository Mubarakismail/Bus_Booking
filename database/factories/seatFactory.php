<?php

namespace Database\Factories;

use App\Models\Bus;
use Faker\Factory as FakerFactory;
use Illuminate\Database\Eloquent\Factories\Factory;

class seatFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $faker = FakerFactory::create();
        $buses = Bus::all()->pluck('id')->toArray();
        return [
            'available' => 0,
            'bus_id' => $faker->randomElement($buses)
        ];
    }
}
