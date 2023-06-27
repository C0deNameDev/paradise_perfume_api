<?php

namespace Database\Factories;

use App\Models\Card;
use App\Models\Client;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Card>
 */
class CardFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'client_id' => Client::inRandomOrder()->first()->id,
        ];
    }

    // public function configure(){
    //     return $this->afterCreating(function(Card $card){
            
    //         $client = Client::inRandomOrder()->first();
    //         $card -> client() -> associate($client);
    //     });
    // }
}
