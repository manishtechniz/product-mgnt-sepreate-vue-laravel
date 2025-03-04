<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\Api\UserController;

/**
 * Public Routes (No Authentication Required)
 */
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

/**
 * Protected Routes (Authentication Required)
 */
Route::middleware('auth:sanctum')->group(function () {
    Route::put('/user/{id}', [UserController::class, 'update']);
});