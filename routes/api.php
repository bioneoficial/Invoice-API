<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\InvoiceController;
use Illuminate\Support\Facades\Route;


Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/login', [AuthController::class, 'login'])->name('login');

Route::middleware('auth:api')->group(function () {
    Route::apiResource('invoices', InvoiceController::class)
        ->except(['store']);

    Route::post('invoices', [InvoiceController::class, 'store'])
        ->middleware('throttle.post.invoices');
    Route::get('/user', [AuthController::class, 'getUser']);
});


Route::get('/test-mongo', function () {
    try {
        \DB::connection('mongodb')->getMongoDB()->listCollections();
        return 'Connected to MongoDB';
    } catch (\Exception $e) {
        return 'Failed to connect to MongoDB: ' . $e->getMessage();
    }
});
