<?php

namespace Database\Factories;

use App\Models\Products;
use App\Models\Categories;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Products>
 */
class ProductsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'category_id' => Categories::factory(),  
            'name' => $this->faker->words(3, true),  
            'description' => $this->faker->sentence(),  
            'price' => $this->faker->randomFloat(2, 5, 500),
        ];
    }
}
