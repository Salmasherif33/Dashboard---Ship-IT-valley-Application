<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Driver extends Model
{
    //protected $guard = 'driver';
    public $timestamps = ["created_at"];
    const UPDATED_AT = null;
    protected $guarded = [];

    use HasFactory;
}
