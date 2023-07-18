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

        $status = ['pending', 'prepared', 'closed'];

        return [
            'quantity' => fake()->randomNumber(1, 10),
            'status' => fake()->randomElement($status),
            'purchase_id' => Purchase::inRandomOrder()->first()->id,
            'bottle_id' => Bottle::inRandomOrder()->first()->id,
            'perfume_id' => Perfume::inRandomOrder()->first()->id,
        ];

    }
}
