<?php

namespace Database\Seeders;

use App\Models\Perfume;
use Database\Factories\PerfumeFactory;
use Illuminate\Database\Seeder;

class PerfumeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Perfume::factory()->times(10)->create();
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
            'Captivating Aura',
        ];

        $sex = ['F', 'M'];

        $seasons = ['winter', 'summer', 'spring', 'fall'];
        $faker = (new PerfumeFactory)->faker;
        for ($i = 0; $i < 10; $i++) {
            Perfume::create([
                'name' => $faker->unique()->randomElement($perfumeNames),
                'sex' => $faker->randomElement($sex),
                'season' => $faker->randomElement($seasons),
                'extra_price' => $faker->numberBetween(0, 1000),
                'picture' => null,
            ]);
        }
    }
}
