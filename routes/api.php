<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\SupplierController;
Route::get('/test', function () {
    return response()->json([
        'message' => 'API Working'
    ]);
});
 
// Route::middleware(['auth:sanctum'])->group(function () {
    // Customers
    Route::prefix('customers')->group(function () {
        Route::get('/', [CustomerController::class, 'getAll']);
        Route::post('/', [CustomerController::class, 'store']);
        Route::post('/{customer}', [CustomerController::class, 'update']);
        Route::delete('/{customer}', [CustomerController::class, 'destroy']);
    });
    Route::prefix('suppliers')->group(function () {
        Route::get('/', [SupplierController::class, 'getAll']);
        Route::post('/', [SupplierController::class, 'store']);
        Route::post('/{supplier}', [SupplierController::class, 'update']);
        Route::delete('/{supplier}', [SupplierController::class, 'destroy']);
    });
 

// });