<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Seat extends Model
{
    use HasFactory;

    protected $fillable = ['availabe', 'bus_id'];


    public function bus()
    {
        return $this->belongsTo(Bus::class);
    }
}
