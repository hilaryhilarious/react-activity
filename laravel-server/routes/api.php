<?php

use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\HRCalculator;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('v1')->group(function () {
    Route::get('/products/queries', [
        ProductsController::class,
        'queries',
    ]);
});

Route::post('/hr/calculate', [HRCalculator::class, 'calculatePayroll']);
Route::apiResource('products', ProductsController::class);
Route::apiResource('categories', CategoriesController::class);


//model is for database
//migration is to create schema
//factory is to create dummy data