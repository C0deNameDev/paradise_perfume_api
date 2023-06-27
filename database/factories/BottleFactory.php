<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Bottle>
 */
class BottleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $volumes = [
            10,
            15,
            100,
            300,
            200
        ];

        $prices = [
            10 => 150,
            15 => 200,
            100 => 400,
            200 => 800,
            300 => 1100,
        ];
        $volume = fake() -> randomElement($volumes);
        return [
            'volume' => $volume,
            'price' => $prices[$volume],
            'picture' => fake()->imageUrl()
        ];
    }
}
