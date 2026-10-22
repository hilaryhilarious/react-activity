<?php

namespace Database\Factories;

use App\Models\Review;
use App\Models\Users;
use App\Models\Products;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Review>
 */
class ReviewFactory extends Factory
{
    protected $model = Review::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => Users::factory(),  
            'product_id' => Products::factory(),  
            'rating' => $this->faker->numberBetween(1, 5),  
            'comment' => $this->faker->paragraph(),
        ];
    }
}
