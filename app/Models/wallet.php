<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class wallet extends Model
{
    use HasFactory;
    protected $fillable =[
        'balance',
        'points',
        'level',
        'wallet_code',
        'amount_spent',
        'walletable_id',
        'walletable_type',
    ];

    public function walletable()
    {
        return $this->morphTo();
    }
}
