<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RwFlight extends Model
{
    protected $table = 'rw_flights';
    protected $fillable = ['code', 'flightnum', 'depicao', 'arricao', 'route', 'tailnum', 'flightlevel', 'deptime', 'arrtime', 'flighttime', 'price', 'flighttype', 'daysofweek'];
    protected $hidden = ['id', 'created_at', 'updated_at'];
}
