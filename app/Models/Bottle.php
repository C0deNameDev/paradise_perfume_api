<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Bottle extends Model
{
    use HasFactory;

    protected $fillable = [
        'volume' => 'required|double',
        'price' => 'required|double',
        'picture' => 'required',
    ];

    public function orders(): BelongsToMany
    {
        return $this->belongsToMany(Order::class, 'bottle_order')->withPivot(['quantity', 'bottle_id', 'order_id', 'status']);
    }
}
