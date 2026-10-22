<?php

namespace Database\Factories;

use App\Models\Address;
use App\Models\Users;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Address>
 */
class AddressFactory extends Factory
{
    protected $model = Address::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        
        return [
            'user_id' => Users::factory(),  
            'street_address' => $this->faker->streetAddress(),  
            'city' => $this->faker->city(), 'state' => $this->faker->state(),  
            'zip' => $this->faker->postcode(),
        ];
    }
}
