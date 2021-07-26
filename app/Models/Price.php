<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Price extends Model
{
    use HasFactory;
    public $timestamps = ["created_at"];
    const UPDATED_AT = null;
    protected $guarded = [];
    protected $table = "price_list";
}
