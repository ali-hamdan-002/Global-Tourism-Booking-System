<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class company_rating extends Model
{
    use HasFactory;
    protected $fillable = [
        'companie_id',
        'user_id',
        'rate',
        'comment',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    //
    public function company()
    {
        return $this->belongsTo(company::class);
    }
}
