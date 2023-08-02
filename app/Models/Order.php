<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        // 'bottle_id',
        'perfume_id',
        // 'quantity',
        'status',
        'client_id',
    ];

    public function bottles(): BelongsToMany
    {
        return $this->belongsToMany(Bottle::class, 'bottle_order')->withPivot(['quantity', 'bottle_id', 'order_id', 'status']);
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function perfume(): BelongsTo
    {
        return $this->belongsTo(Perfume::class);
    }
}
