<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class offer_rating extends Model
{
    use HasFactory;
    protected $fillable = [
        'offer_id',
        'user_id',
        'rate',
        'comment',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    //
    public function offer()
    {
        return $this->belongsTo(Offer::class);
    }
}
