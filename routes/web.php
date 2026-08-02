<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Guest
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return Inertia::render('Auth/Login', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

/*
|--------------------------------------------------------------------------
| Dashboard
|--------------------------------------------------------------------------
*/

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Profile
    |--------------------------------------------------------------------------
    */

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    /*
    |--------------------------------------------------------------------------
    | Example
    |--------------------------------------------------------------------------
    */

    Route::get('/example', function () {
        return Inertia::render('Example/Index');
    })->name('example');

    /*
    |--------------------------------------------------------------------------
    | Users
    |--------------------------------------------------------------------------
    */

    Route::get('/users', [UserController::class, 'index'])
        ->name('users.index');

    /*
    |--------------------------------------------------------------------------
    | Academic Management
    |--------------------------------------------------------------------------
    */

    Route::prefix('academic')
        ->name('academic.')
        ->group(function () {

            /*
            |--------------------------------------------------------------------------
            | Faculties
            |--------------------------------------------------------------------------
            */

            Route::get('/faculties', function () {
                // dd("herer");
                return Inertia::render('Academic/Faculty/Index');
            })->name('faculties.index');

            /*
            |--------------------------------------------------------------------------
            | Disciplines
            |--------------------------------------------------------------------------
            */

            Route::get('/disciplines', function () {
                return Inertia::render('Academic/Discipline/Index');
            })->name('disciplines.index');

            /*
            |--------------------------------------------------------------------------
            | Academic Sessions
            |--------------------------------------------------------------------------
            */

            Route::get('/sessions', function () {
                return Inertia::render('Academic/Session/Index');
            })->name('sessions.index');

            /*
            |--------------------------------------------------------------------------
            | Academic Classes
            |--------------------------------------------------------------------------
            */

            Route::get('/classes', function () {
                return Inertia::render('Academic/Class/Index');
            })->name('classes.index');

        });

});

require __DIR__.'/auth.php';