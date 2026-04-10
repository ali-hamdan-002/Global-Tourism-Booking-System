<?php

namespace App\Models;

use Laravel\Passport\HasApiTokens;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;


class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
   // protected $guard = ["api"];

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'phone_number',
        'location',
        'annual_income',
        'country',
        'gender',
        'birth',
        'status',
        'wallet_id'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    public function wallet()
    {
        return $this->morphOne(wallet::class,'walletable');
    }
    public function photos()
    {
        return $this->morphMany(photo::class,'photoable');
    }

    public function offer_rating()
    {
        return $this->hasMany(offer_rating::class);
    }

    public function company_rating()
    {
        return $this->hasMany(company_rating::class);
    }
    public function favourites()
    {
        return $this->belongsToMany(offer::class,'favourites')->wherePivot('status','favourite');
    }
    public function deletes()
    {
        return $this->belongsToMany(offer::class,'favourites')->wherePivot('status','delete');
    }
    public function bookings()
    {
        return $this->belongsToMany(offer::class,'bookings');
    }
    public function card()
{
    return $this->hasMany(card::class);
}


}
