<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/product', [App\Http\Controllers\ProductController::class, 'index']);
// route - http method (get) - uri - controller/class - method
//http method - get, post, put, delete, patch

Route::get('/school', [App\Http\Controllers\ProductController::class, 'read']);
Route::get('/function-parameter/{name}', [App\Http\Controllers\ProductController::class, 'greet']);