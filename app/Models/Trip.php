<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trip extends Model
{
    use HasFactory;

    protected $fillable = [
        'arrival_time', 'departure_time', 'type'
    ];

    public function buses()
    {
        return $this->belongsToMany(Bus::class, 'trip_buses');
    }
    public function stations()
    {
        return $this->belongsToMany(Station::class, 'trip_stations');
    }
}
