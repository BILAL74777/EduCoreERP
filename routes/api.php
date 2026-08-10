<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Academic\FacultyController;
use App\Http\Controllers\Academic\DisciplineController;
use App\Http\Controllers\Academic\AcademicClassController;
use App\Http\Controllers\Academic\AcademicSessionController;
use App\Http\Controllers\Academic\StudentController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Finance\StudentFeePaymentController;
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
        Route::prefix('sessions')->group(function () {

            Route::get('/', [AcademicSessionController::class, 'index']);
            Route::post('/', [AcademicSessionController::class, 'store']);
            Route::get('/dropdown', [AcademicSessionController::class, 'getDropdown']);

            Route::get('/{academicSession}', [AcademicSessionController::class, 'show']);
            Route::put('/{academicSession}', [AcademicSessionController::class, 'update']);
            Route::delete('/{academicSession}', [AcademicSessionController::class, 'destroy']);
            Route::patch('/{academicSession}/status', [AcademicSessionController::class, 'changeStatus']);

        }); 
        Route::prefix('students')->group(function () { 

            Route::get('/', [StudentController::class,'index']);
            Route::post('/', [StudentController::class,'store']);
            Route::put('/', [StudentController::class,'update']);
             // Student Admission Dropdowns
            Route::get('/dropdowns', [StudentController::class, 'getDropdowns']);
            Route::get('/disciplines/{faculty}', [StudentController::class, 'getDisciplines']);
            Route::get('/classes/{discipline}', [StudentController::class, 'getClasses']);

            Route::get('/{student}', [StudentController::class,'show']);
            Route::put('/{student}', [StudentController::class,'update']);
            Route::delete('/{student}', [StudentController::class,'destroy']);
        }); 
    });

        
        
    Route::prefix('finance')->group(function () { 
        Route::prefix('fee-collection')->group(function () { 
            Route::get('/', [StudentFeePaymentController::class,'index']);
            Route::get('/students', [StudentFeePaymentController::class,'students']);
            Route::get('/students/{student}/fee', [StudentFeePaymentController::class,'fee']);
            Route::post('/', [StudentFeePaymentController::class,'store']);
            Route::delete('/{payment}', [StudentFeePaymentController::class,'destroy']);
        });
    });


});