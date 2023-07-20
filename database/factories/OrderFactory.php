<?php

namespace Database\Factories;

use App\Models\Bottle;
use App\Models\Client;
use App\Models\Perfume;
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
            // 'quantity' => fake()->randomNumber(1, 10),
            'status' => fake()->randomElement($status),
            // 'bottle_id' => Bottle::inRandomOrder()->first()->id,
            'perfume_id' => Perfume::inRandomOrder()->first()->id,
            'client_id' => Client::inRandomOrder()->first()->id,
        ];

    }
}
