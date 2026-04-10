<?php

namespace App\Models;

use Laravel\Passport\HasApiTokens;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable ;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'phone_number',
        'image',
        'role',
        'wallet_id'
    ];


    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function companies()
    {
        return $this->hasOne(company::class,'admin_id');
    }

     public function wallet()
    {
        return $this->belongsTo(wallet::class,'wallet_id');
    }

}
