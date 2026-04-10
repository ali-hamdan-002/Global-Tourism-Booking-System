<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class photo extends Model
{
    use HasFactory;
    protected $fillable = ['filename', 'photoable_id', 'photoable_type', 'url'];
    public function photoable()
    {
        return $this->morphTo();
    }
}
