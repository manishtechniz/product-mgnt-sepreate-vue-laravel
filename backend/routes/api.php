<?php

use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\API\ProductController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\Api\UserController;

/**
 * Public Routes (No Authentication Required)
 */
Route::post('/register', [AuthController::class, 'register'])->name('register');

Route::post('/login', [AuthController::class, 'login'])->name('login');

/**
 * Protected Routes (Authentication Required)
 */
Route::middleware('auth:sanctum')->group(function () {
    /**
     * User Profile.
     */
    Route::put('/user/{id}', [UserController::class, 'update'])->name('profile_update');

    /**
     * Category
     */
    Route::group(['prefix' => 'category'], function() {
        Route::get('/', [CategoryController::class, 'index'])->name('category');

        Route::post('/', [CategoryController::class, 'store'])->name('category_create');

        Route::post('/{id}', [CategoryController::class, 'update'])->name('category_update');

        Route::delete('/{id}', [CategoryController::class, 'destroy'])->name('category_destroy');
    });

    /**
     * Product
     */
    Route::group(['prefix' => 'product'], function() {
        Route::get('/', [ProductController::class, 'index'])->name('product');

        Route::post('/', [ProductController::class, 'store'])->name('product_create');

        Route::put('/{id}', [ProductController::class, 'update'])->name('product_update');

        Route::delete('/{id}', [ProductController::class, 'destroy'])->name('product_destroy');
    });
});