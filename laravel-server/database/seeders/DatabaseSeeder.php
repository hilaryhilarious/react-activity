<?php

namespace Database\Seeders;

use App\Models\Users;
use App\Models\Products;
use App\Models\Categories;
use App\Models\Review;
use App\Models\Address;
use App\Models\Order;
use App\Models\OrderProduct;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        // Product::factory()->count(20)->create();

        $categories = Categories::factory(5)->create();
        
        $products = Products::factory(2000) 
            ->recycle($categories) 
            ->create();
        
        $users = Users::factory(20)->create();

        $users->each(function ($user) { 
            Address::factory(rand(1, 2)) 
                ->for($user, 'user') 
                ->create(); 
        });

        $orders = $users->flatMap(function ($user) { 
            return Order::factory(rand(0, 5)) 
                ->for($user, 'user') 
                ->create(); 
        });

        $orders->each(function ($order) use ($products) { 
            $orderProducts = $products->random(rand(1, 4)); 
            foreach ($orderProducts as $product) { 
                OrderProduct::factory() 
                    ->for($order, 'order') 
                    ->for($product, 'product') 
                    ->create(); 
            } 
        });

        $products->each(function ($product) use ($users) { 
            $reviewers = $users->random(rand(0, 8)); 
            foreach ($reviewers as $user) { 
                Review::factory() 
                    ->for($user, 'user') 
                    ->for($product, 'product') 
                    ->create(); 
            } 
        }); 
    }
}
