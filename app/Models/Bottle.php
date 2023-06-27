<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bottle extends Model
{
    use HasFactory;

    protected $fillable = [
        'volume' => 'required|double',
        'price' => 'required|double',
        'picture' => 'required'
    ];
}
