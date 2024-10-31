<?php

use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Public Routes
Route::post('register', [UserController::class, 'register']);
Route::post('login', [UserController::class, 'login']);
// Route::get('invoice', [InvoiceController::class, 'download']);
// Protected Routes
Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('logout', [UserController::class, 'logout']);
    Route::get('user', [UserController::class, 'show']);

    // Invoice
    Route::apiResource('invoice', InvoiceController::class);
    Route::post('invoice/download', [InvoiceController::class, 'download']);
});
