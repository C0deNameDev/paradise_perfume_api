<?php

namespace Database\Seeders;

use App\Models\User;
use Database\Factories\UserFactory;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = (new UserFactory)->faker;
        for ($i = 0; $i < 10; $i++) {
            User::create([
                'email' => $faker->unique()->email,
                'email_verified_at' => null,
                'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
                'profile_picture' => $faker->imageUrl,
                'one_time_token' => $faker->text,
            ]);
        }
    }
}
