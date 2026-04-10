<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class booking extends Model
{
    use HasFactory;
    protected $fillable = [
        'number_of_people',
        'total_price',
        'status',
        'user_id',
        'offer_id',
    ];
    public function offer()
    {
        return $this->belongsTo(Offer::class);
    }

    public function user()
    {
        return $this->belongsTo(user::class);
    }
}
