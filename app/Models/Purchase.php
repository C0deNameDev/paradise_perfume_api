<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Card;
use Illuminate\Database\Eloquent\Relations\HasMany;
class Purchase extends Model
{
    use HasFactory;

    protected $fillable = [
        'perfume',
        'bottle',
        'totalPrice',
        'date',
        'card_id'
    ];

    public function card(): BelongsTo
    {
        return $this->belongsTo(Card::class);
    }

    public function orders(): HasMany
    {
        return $this -> hasMany(Order::class);
    }
}
