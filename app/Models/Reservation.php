<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = ['trip_id', 'seat_id', 'start_station_id', 'end_station_id', 'user_id'];

    public function trip()
    {
        return $this->belongsTo(Trip::class);
    }
    public function seat()
    {
        return $this->belongsTo(Seat::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
