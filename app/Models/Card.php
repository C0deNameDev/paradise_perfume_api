<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Purchase;
class Card extends Model
{
    use HasFactory;
    protected $fillable = [
        'client_id'
    ];
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function purchases(): HasMany
    {
        return $this -> hasMany(Purchase::class);
    }
}
