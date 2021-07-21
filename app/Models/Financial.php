<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Financial extends Model
{
    public $timestamps = ["created_at"];
    public $timestamps2 = ["updated_at"];

    protected $guarded = [];

    use HasFactory;
}
