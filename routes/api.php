<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->controller(\App\Http\Controllers\AuthController::class)->group(function () {
    Route::post('register', 'register');
    Route::post('login', 'login');
    Route::post('logout', 'logout')->middleware('auth:sanctum');
});


Route::middleware('auth:sanctum')->group(function () {
    Route::get('products', [\App\Http\Controllers\ProductController::class, 'index']);
    Route::get('profile', [\App\Http\Controllers\UserController::class, 'show']);
    Route::post('transactions', [\App\Http\Controllers\TransactionController::class, 'store']);
});


