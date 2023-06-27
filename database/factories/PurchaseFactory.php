<?php

namespace Database\Factories;

use App\Models\Bottle;
use App\Models\Card;
use App\Models\Perfume;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Purchase>
 */
class PurchaseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $perfumeName = Perfume::inRandomOrder()->first()->name;
        $bottle = Bottle::inRandomOrder()->first();
        return [
            'perfume' => $perfumeName,
            'bottle' => $bottle->volume.' ml',
            'totalPrice' => rand(150, 5000),
            'date' => Carbon::now(),
            'card_id' => Card::inRandomOrder()->first()->id
        ];
    }
}
