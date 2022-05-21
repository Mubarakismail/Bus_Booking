<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class seatsResource extends JsonResource
{
    private $ids;

    public function __construct($ids)
    {
        $this->ids = $ids;
    }

    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'data' => [
                "seats" => $this->ids
            ],
        ];
    }
}
