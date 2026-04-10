<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class card extends Model
{
    use HasFactory;
    protected $fillable = [
        'card_number',
        'type',
        'security_code',
        'user_id',

    ];
 
    public function user()
    {
        return $this->belongsTo(user::class,'user_id');
    }
}
