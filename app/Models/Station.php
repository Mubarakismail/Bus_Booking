<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Station extends Model
{
    use HasFactory;

    protected $fillable = ['station_name', 'station_number'];

    public function trips()
    {
        return $this->belongsToMany(Trip::class, 'trip_stations');
    }
}
