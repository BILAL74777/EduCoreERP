<?php

use Illuminate\Support\Facades\Route;
 
use App\Http\Controllers\UserController;
Route::get('/test', function () {
    return response()->json([
        'message' => 'API Working'
    ]);
});
 
Route::middleware(['auth:sanctum'])->group(function () {
   
    Route::prefix('users')->group(function () {
           Route::get('/', [UserController::class, 'getAll']);
            Route::post('/', [UserController::class, 'store']);
            Route::post('/{user}', [UserController::class, 'update']);
            Route::delete('/{user}', [UserController::class, 'destroy']);
    });

});