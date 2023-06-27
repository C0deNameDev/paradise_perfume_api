<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Purchase;
class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'perfume',
        'bottle',
        'quantity',
        'purchase_id',
        'status'
    ];

    public function purchase(): BelongsTo
    {
        return $this -> belongsTo(Purchase::class);
    }
}
