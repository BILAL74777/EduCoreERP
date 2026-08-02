<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Academic\FacultyController;
use App\Http\Controllers\Academic\DisciplineController;

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
            Route::get('/dropdown', [FacultyController::class, 'getDropdown']);
            Route::get('/{faculty}', [FacultyController::class, 'show']);
            Route::put('/{faculty}', [FacultyController::class, 'update']);
            Route::delete('/{faculty}', [FacultyController::class, 'destroy']);
            Route::patch('/{faculty}/status', [FacultyController::class, 'changeStatus']);
        });
        Route::prefix('disciplines')->group(function () {
            Route::get('/', [DisciplineController::class, 'index']);
            Route::post('/', [DisciplineController::class, 'store']);
            Route::get('/{discipline}', [DisciplineController::class, 'show']);
            Route::put('/{discipline}', [DisciplineController::class, 'update']);
            Route::delete('/{discipline}', [DisciplineController::class, 'destroy']);
            Route::patch('/{discipline}/status', [DisciplineController::class, 'changeStatus']);
            Route::get('/dropdown', [DisciplineController::class, 'getDropdown']);
        });

    });

});