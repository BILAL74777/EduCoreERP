<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Academic\StudentController;

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Guest
|--------------------------------------------------------------------------
*/

// Route::get('/', function () {
//     return Inertia::render('Auth/Login', [
//         'canLogin' => Route::has('login'),
//         'canRegister' => Route::has('register'),
//         'laravelVersion' => Application::VERSION,
//         'phpVersion' => PHP_VERSION,
//     ]);
// });


/*
|--------------------------------------------------------------------------
| Front end 
|--------------------------------------------------------------------------

*/


Route::get('/', function () { 
        return Inertia::render('Frontend/Index');
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
                return Inertia::render('Academic/AcademicSessions/Index');
            })->name('sessions.index');

            /*
            |--------------------------------------------------------------------------
            | Academic Classes
            |--------------------------------------------------------------------------
            */
 
            Route::get('/classes', function () {
                return Inertia::render('Academic/Classes/Index');
            })->name('classes.index');

            /*
            |--------------------------------------------------------------------------
            | Students
            |--------------------------------------------------------------------------
            */
 
            Route::get('/students', function () {
                return Inertia::render('Academic/Students/Index');
            })->name('students.index');
            Route::get('/students/create', function () {
                return Inertia::render('Academic/Students/Create');
            })->name('students.create');
             
 
             Route::get('/students/{student}', [StudentController::class, 'show'])
        ->name('students.show');
             Route::get('/student/{student}', [StudentController::class, 'edit'])
        ->name('student.edit');

         /*
            |--------------------------------------------------------------------------
            | Finance
            |--------------------------------------------------------------------------
            */ 
            
            });
            Route::get('/finance/collections', function () {
              
                return Inertia::render('Finance/FeeCollection/Index');
                 })->name('finance.collections');

                 Route::get('/attendance', function () {
return Inertia::render('Attendance/Index');
})->name('attendance.index');

});

require __DIR__.'/auth.php';
