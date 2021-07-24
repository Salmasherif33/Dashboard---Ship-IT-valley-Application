<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Truck extends Model
{
    use HasFactory;
    use HasFactory;
    public $timestamps = ["created_at"];
    const UPDATED_AT = null;
    protected $guarded = [];
    protected $table = "trucks_types";
}
