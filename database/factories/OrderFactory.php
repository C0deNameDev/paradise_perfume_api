<?php

namespace Database\Factories;

use App\Models\Bottle;
use App\Models\Perfume;
use App\Models\Purchase;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $perfume = Perfume::inRandomOrder() -> first();
        $bottle = Bottle::inRandomOrder() -> first();
        $status = ['pending', 'prepared', 'closed'];
        return [
            'perfume' => $perfume -> name,
            'bottle' => $bottle->volume.' ml',
            'quantity'=> fake() -> randomNumber(1, 10),
            'status' => fake() -> randomElement($status),
            'purchase_id' => Purchase::inRandomOrder()->first()->id
        ];
    }
}
