<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Academic\FacultyController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->group(function () {

    Route::prefix('users')->group(function () {
           Route::get('/', [UserController::class, 'getAll']);
            Route::post('/', [UserController::class, 'store']);
            Route::post('/{user}', [UserController::class, 'update']);
            Route::delete('/{user}', [UserController::class, 'destroy']);
    });
    /*
    |--------------------------------------------------------------------------
    | Academic
    |--------------------------------------------------------------------------
    */

    Route::prefix('academic')->group(function () {

        /*
        |--------------------------------------------------------------------------
        | Faculties
        |--------------------------------------------------------------------------
        */

        Route::prefix('faculties')->group(function () {

            Route::get('/', [FacultyController::class, 'index']);

            Route::post('/', [FacultyController::class, 'store']);

            Route::get('/{faculty}', [FacultyController::class, 'show']);

            Route::put('/{faculty}', [FacultyController::class, 'update']);

            Route::delete('/{faculty}', [FacultyController::class, 'destroy']);

            Route::patch('/{faculty}/status', [FacultyController::class, 'changeStatus']);

        });

    });

});