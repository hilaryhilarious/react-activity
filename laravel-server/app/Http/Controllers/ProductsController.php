<?php

namespace App\Http\Controllers;

use App\Traits\ApiResponses;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\V1\ProductResource;
use App\Models\Products;
use Ramsey\Uuid\Type\Decimal;

class ProductsController extends Controller
{
    use ApiResponses;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //index
        // $products = Product::all();
        // $result = [];

        // foreach ($products as $product){
        //     $result[] = [
        //         'id' => $product->id,
        //         'name' => $this->formatProductName($product->name),
        //         'slug' => $product->slug,
        //         'category' => $product->category,
        //         'price' => $product->price,
        //         'quantity' => $product->quantity,
        //         'status' => $product->status,
        //     ];
        // }

        // return response()->json([
        //     'status' => true,
        //     'message' => 'Products retrieved successfully.',
        //     'data' => $result
        // ], 200);

        $products = Products::with('category') 
        ->paginate(10); 
        
        return $this->ok('Products retrieved successfully',  
            ProductResource::collection($products) 
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Products $product)
    {
        $product->load('category'); 
        return $this->ok('Product retrieved successfully', new ProductResource($product)); 

        //show using slug
        // $product = Product::where('slug', $slug)->first();

        // if(!$product){
        //     return response()->json([
        //         'status' => false,
        //         'message' => 'Product not found.'
        //     ], 404);
        // }

        // return response()->json([
        //     'status' => true,
        //     'message' => 'Product retrieved successfully.',
        //     'data' => [
        //         'id' => $product->id,
        //         'name' => $this->formatProductName($product->name),
        //         'slug' => $product->slug,
        //         'category' => $product->category,
        //         'price' => $product->price,
        //         'quantity' => $product->quantity,
        //         'status' => $product->status,
        //     ]
        // ], 200);
    }

    //create user-defined function
    private function formatProductName(/*$name*/){
        // return ucwords(
        //     strtolower(
        //         trim($name)
        //     )
        // );
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function queries(){
    // A. QUERYING TABLES 
    // Get the name and price columns from the table. 
    $nameAndPrice = Products::select('name', 'price')->get(); 

    // Load products with their category (avoids N+1 queries). 
    $productsWithCategory = Products::with('category')->get(); 

    // product id and name, ordered by price ascending.
    $productsOrderedByPrice = Products::select('id', 'name') 
        ->orderBy('price', 'asc') 
        ->get(); 

    // first 5 rows
    $firstFiveProducts = Products::limit(5)->get(); 

    // B. FILTERING DATA 
    // price more than or equal to 3
    $priceGreaterThanOrEqual = Products::where('price', '>=', 3)->get(); 

    // Get products in the 'Electronics' category. 
    $electronics = Products::whereHas('category', function ($q) { 
        $q->where('name', 'Electronics'); 
    })->get();
 
    // C. AGGREGATING DATA 
    // total price across all products
    $totalPrice = Products::sum('price'); 

    // average price per product. 
    $averagePrice = round(Products::avg('price'), 2); 

    // product with the highest price. 
    $highestPrice = Products::max('price'); 

    // Get the product with the lowest price. 
    $lowestPrice = Products::min('price'); 

    
    // D. GROUPING, FILTERING, AND SORTING 
    // Get the total price of products for each category. 
    $totalPricePerCategory = Products::join( 
        'categories', 
        'products1.category_id', 
        '=', 
        'categories.id' 
        ) 
        ->select( 
        'categories.name', 
        DB::raw('SUM(products1.price) as total_price') 
        ) 
        ->groupBy('categories.name') 
        ->get(); 

    // Get the average price for each category. 
    $averagePricePerCategory = Products::join( 
        'categories', 
        'products1.category_id', 
        '=', 
        'categories.id' 
        ) 
        ->select( 
            'categories.name', 
            DB::raw('ROUND(AVG(products1.price),2) as avg_price') 
        ) 
        ->groupBy('categories.name') 
        ->get(); 

    // Get the product with the lowest price per category. 
    $lowestPricePerCategory = Products::join( 
        'categories', 
        'products1.category_id', 
        '=', 
        'categories.id' 
        ) 
        ->select( 
            'categories.name', 
            DB::raw('MIN(products1.price) as min_price') 
        ) 
        ->groupBy('categories.name') 
        ->get(); 

    // Get the average price per category, sorted ascending. 
    $averagePriceSorted = Products::join( 
        'categories', 
        'products1.category_id', 
        '=', 
        'categories.id' 
        ) 
        ->select( 
        'categories.name', 
        DB::raw('ROUND(AVG(products1.price),2) as avg_price') 
        ) 
        ->groupBy('categories.name') 
        ->orderBy('avg_price', 'asc') 
        ->get(); 
    
    // Get the average price for 'Electronics' and 'Books' categories. 
    $electronicsAndBooks = Products::join( 
        'categories', 
        'products1.category_id', 
        '=', 
        'categories.id' 
        ) 
        ->whereIn('categories.name', ['Electronics', 'Books']) 
        ->select( 
        'categories.name', 
        DB::raw('AVG(products1.price) as avg_price') 
        ) 
        ->groupBy('categories.name') 
        ->get();

    // RETURN ALL RESULTS AS JSON 
    $queryResults = [
    'name_and_price' => $nameAndPrice,
    'products_with_category' => $productsWithCategory,
    'products_ordered_by_price' => $productsOrderedByPrice,
    'first_five_products' => $firstFiveProducts,

    'price_greater_than_or_equal' => $priceGreaterThanOrEqual,
    'electronics' => $electronics,

    'total_price' => $totalPrice,
    'average_price' => $averagePrice,
    'highest_price' => $highestPrice,
    'lowest_price' => $lowestPrice,

    'total_price_per_category' => $totalPricePerCategory,
    'average_price_per_category' => $averagePricePerCategory,
    'lowest_price_per_category' => $lowestPricePerCategory,
    'average_price_sorted' => $averagePriceSorted,
    'electronics_and_books' => $electronicsAndBooks,
    ];

    return $this->success(
        'Query results retrieved successfully',
    $queryResults
    );
    }
}
