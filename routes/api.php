<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Academic\FacultyController;
use App\Http\Controllers\Academic\DisciplineController;
use App\Http\Controllers\Academic\AcademicClassController;

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
            Route::get('/dropdown', [DisciplineController::class, 'getDropdown']);
            Route::get('/{discipline}', [DisciplineController::class, 'show']);
            Route::put('/{discipline}', [DisciplineController::class, 'update']);
            Route::delete('/{discipline}', [DisciplineController::class, 'destroy']);
            Route::patch('/{discipline}/status', [DisciplineController::class, 'changeStatus']);
        });
        Route::prefix('classes')->group(function () {
            Route::get('/', [AcademicClassController::class, 'index']);
            Route::post('/', [AcademicClassController::class, 'store']);
            Route::get('/dropdown', [AcademicClassController::class, 'getDropdown']);

            Route::get('/{academicClass}', [AcademicClassController::class, 'show']);
            Route::put('/{academicClass}', [AcademicClassController::class, 'update']);
            Route::delete('/{academicClass}', [AcademicClassController::class, 'destroy']);
            Route::patch('/{academicClass}/status', [AcademicClassController::class, 'changeStatus']);
        });

    });

});