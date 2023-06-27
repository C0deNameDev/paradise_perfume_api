<?php

namespace Database\Factories;
use App\Models\Characteristic;
use App\Models\Perfume;
use Illuminate\Support\Str;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Perfume>
 */


class PerfumeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $perfumeNames = [
        'Enchanted Elixir',
            'Midnight Serenade',
            'Velvet Whispers',
            'Pure Bliss',
            'Eternal Essence',
            'Whispering Jasmine',
            'Mystique Noir',
            'Radiant Bloom',
            'Golden Amber',
            'Captivating Aura'
        ];
        return [
            'name' => $this->faker->unique()->randomElement($perfumeNames),
            'priceExtra' => $this->faker->numberBetween(0, 1000),
            'picture' => $this->faker->imageUrl()
        ];
    }


    /**
     * Configure the model factory's afterCreating callback.
     *
     * @return $this
     */
    public function configure()
    {
        return $this->afterCreating(function (Perfume $perfume) {
            $number = fake() -> numberBetween(1, 100);
            $characteristics = Characteristic::pluck('id')->random($number);
            $perfume->characteristics()->attach($characteristics);
        });
    }
}

