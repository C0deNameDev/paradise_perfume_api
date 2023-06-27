<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Models\Characteristic;
class Perfume extends Model
{
    use HasFactory;
    /**
     * 
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'priceExtra',
        'picture'
    ];

    public function characteristics(): BelongsToMany
    {
        return $this->belongsToMany(Characteristic::class);
    }
}
