<?php

namespace Database\Factories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class tripFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $going_departure = Carbon::now();
        $going_arrival = Carbon::now()->addHours(rand(1, 10));
        return [
            'arrival_time' => $going_arrival,
            'departure_time' => $going_departure,
            'type' => rand(1, 2)
        ];
    }
}
