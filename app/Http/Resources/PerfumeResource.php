<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PerfumeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'name' => $this->name,
            'picture' => $this->picture,
            'extra_price' => $this->extra_price,
            'id' => $this->id,
            'sex' => $this->sex,
            'season' => $this->season,

        ];
    }
}
