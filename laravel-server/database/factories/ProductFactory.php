<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Support\Str;
use App\Models\Categories;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    
    public function definition(): array
    {   //indexed array
        // $categories = [
        //     'Hardware',
        //     'Electrical',
        //     'Plumbing'
        // ];

        //Multidimensional array
        // $products = [
        //     [
        //         'category' => $categories[0],
        //         'name' => 'hammer',
        //         'price' => 250,
        //     ],
        //     [
        //         'category' => $categories[1],
        //         'name' => 'extension wire',
        //         'price' => 450,
        //     ],
        //     [
        //         'category' => $categories[2],
        //         'name' => 'water pipe',
        //         'price' => 180,
        //     ],
        // ];

        //select random products
        // $product = $this->faker->randomElement($products);

        //format product name
        // $name = ucwords(
        //     strtolower(
        //         trim($product['name'])
        //     )
        // );

        // return [
        //     'name' => $name,
        //     'slug' => Str::slug($name),
        //     'category' => ucwords(strtolower(trim($product['category']))
        //     ),
        //     'price' => $product['price'],
        //     'quantity' => $this->faker->numberBetween(1,100),
        //     'status' => 'Available',
        // ];

        return [
            'category_id' => Categories::factory(),  
            'name' => $this->faker->words(3, true),  
            'description' => $this->faker->sentence(),  
            'price' => $this->faker->randomFloat(2, 5, 500),
        ];
    }
}

