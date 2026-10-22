<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductsController;

//Route::apiResource('products', ProductController::class);

// Route::get('/', function (Request $request){
//     return $request-> user();
// }) ->middleware('auth:sanctum');

// Route::get('/products', [
//     ProductsController::class,
//     'queries'
// ]);

Route::get('/', function () {
    return response()->json([
        'message' => 'Product API v1 is running'
    ]);
});


Route::get('products/queries', [ProductsController::class, 'queries'])->name('products.queries');
// 2. Define standard resource routes for index, show, store, update, destroy
Route::apiResource('products', ProductsController::class);
//apiResource - index, store, show, destroy, update(put, patch)
//SOLID principle
//S -single
// create api - php artisan install:api
// view route list - php artisan route:list

// creating class
// for base class - php artisan make:request Api/V1/BaseProductRequest
// derived class- php artisan make:request Api/V1/StoreProductRequest

//Abstract Class - hide detailed part in class - in child or derived body, create concrete class
//php artisan make:class Http/Filters/V1/QueryFilter (-- abstract) - optional
//php artisan make:class Http/Filters/V1/ProductFilter

//Inheritance - class StoreProductRequest extends BaseProductRequest
//Encapsulation - private(within class only) and protected(within class but can access by derived class)
//constructor method - to avoid modified field in base class (get, set)
//Trait - no need to inherit 

//midterm exam
//migration(schema)-relationship in model-factory(dummy-data)-seeder-controller(all query) - display in postman
//-mfc - model, migration, factory, controller
//-a  -> all

//analyze relationship - identify child and parent
//user model - belongsTo 
//fillable, hasFactory, relationship

//clone repository - composer install and npm install