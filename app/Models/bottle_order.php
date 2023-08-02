<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class bottle_order extends Pivot
{
    protected $table = 'bottle_order';

    // Add any additional columns in the pivot table to the $fillable array
    protected $fillable = [
        'quantity',
        'bottle_id',
        'order_id',
        'status',
    ];
}
