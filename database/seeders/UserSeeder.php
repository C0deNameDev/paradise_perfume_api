<?php

namespace Database\Seeders;

use App\Models\SuperAdmin;
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
        User::create([
            'email' => 'paradise.perfume.dm@gmail.com',
            'email_verified_at' => null,
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
            'profile_picture' => 'admin',
            'one_time_token' => null,
            'person_type' => SuperAdmin::class,
            'person_id' => 1,
        ]);
        for ($i = 0; $i < 10; $i++) {
            User::create([
                'email' => $faker->unique()->email,
                'email_verified_at' => null,
                'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
                'profile_picture' => null,
                'one_time_token' => null,
            ]);
        }
    }
}
