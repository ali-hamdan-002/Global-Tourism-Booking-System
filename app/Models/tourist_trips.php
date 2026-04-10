<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tourist_trips extends Model
{
    use HasFactory;
    protected $fillable = [
        'trip_date',
        'start_hour',
        'trip_end_date',
        'end_hour',
        'source',
        'destination',
        'cost',
        'capacity',
        'offer_id'


    ];
    protected $casts = [
        'trip_date' => 'date', // تحديد العمود كتاريخ
        'start_hour' => 'datetime', // اختياري: تحديد العمود كزمن وتاريخ
        'end_date' => 'date', // تحديد العمود كتاريخ
        'end_hour' => 'datetime', // اختياري: تحديد العمود كزمن وتاريخ
    ];


// public function company()
// {
//     return $this->belongsTo(company::class);
// }
public function offer()
{
    return $this->belongsTo(offer::class);
}
public function trip_stations()
{
    return $this->hasMany(trip_station::class,"tourist_trip_id");
}



}
