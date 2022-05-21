<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TripsResource extends JsonResource
{
    private $trips;

    public function __construct($trips)
    {
        $this->trips = $trips;
    }
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'data' => [
                'trips' => $this->trips,
            ],
            'message' => 'all trips that have available seats retrieved',
        ];
    }
}
