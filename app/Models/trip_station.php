<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class trip_station extends Model
{
    use HasFactory;
    //image
    protected $fillable = [
        'description',
        'stations_strat_houre',
        'stations_end_houre',
       'transportation',

       'hotel',
       'restaurant',
      'tourist_trip_id',
   ];
    public function tourist_trips()
    {
        return $this->belongsTo(tourist_trips::class);
    }
    public function photos()
    {
        return $this->morphMany(photo::class,'photoable');
    }
}
