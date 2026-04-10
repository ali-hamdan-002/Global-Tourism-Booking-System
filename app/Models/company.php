<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class company extends Model
{
    use HasFactory;
    //image
    protected $fillable = [
        'id',
        'name',
        'location',
        'descr',
        'phone',
        'service_type',
        'status',
        'admin_id',
        'wallet_id'

    ];

public function admins()
{
    return $this->belongsTo(Admin::class);
}

public function offer()
{
    return $this->hasMany(offer::class);
}
public function wallet()
{
    return $this->morphOne(wallet::class,'walletable');
}

public function photos()
{
    return $this->morphMany(photo::class,'photoable');
}
public function company_rating()
{
    return $this->hasMany(company_rating::class);
}

}
