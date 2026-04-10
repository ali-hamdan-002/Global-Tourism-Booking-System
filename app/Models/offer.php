<?php

namespace App\Models;

use App\Models\company;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class offer extends Model
{
    use HasFactory;
    protected $fillable = [
'id',
         'trip_date',
         'start_hour',
         'status',
        'companie_id',
        'source',
        'destination'
    ];

    public function company()
{
    return $this->belongsTo(company::class,'companie_id');
}

public function tourist_trips()
{
    return $this->hasOne(tourist_trips::class);
}
public function photos()
{
    return $this->morphMany(photo::class,'photoable');
}

public function offer_rating()
{
    return $this->hasMany(offer_rating::class);
}
public function favourites()
{
    return $this->belongsToMany(User::class,'favourites');
}
public function bookings()
{
    return $this->belongsToMany(User::class,'bookings');
}
}
