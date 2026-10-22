<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\Users;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Order>
 */
class OrderFactory extends Factory
{
    protected $model = Order::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => Users::factory(),  
            'date' => $this->faker ->dateTimeBetween('-1 year', 'now') ->format('Y-m-d'),
        ];
    }
}
