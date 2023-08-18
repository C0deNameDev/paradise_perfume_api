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
    // $factory = (new PerfumeFactory);
        // Perfume::factory()->count(10)->make();
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
        

        for ($i = 0; $i < 10; $i++) {
            Perfume::create([
                'name' => $perfumeNames[array_rand($perfumeNames)],
                'sex' => $sex[array_rand($sex)],
                'season' => $seasons[array_rand($seasons)],
                'extra_price' => rand(0, 200),
                'picture' => null,
            ]);
        }
    }
}
