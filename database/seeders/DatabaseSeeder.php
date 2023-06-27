<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            ClientSeeder::class,
            CharacteristicSeeder::class,
            PerfumeSeeder::class,
            BottleSeeder::class,
            CardSeeder::class,
            PurchaseSeeder::class,
            OrderSeeder::class
        ]);
        
    }
}
