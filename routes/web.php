<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CustomerController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // 
    Route::get('/example', function () {
    return Inertia::render('Example/Index');
})->name('example');

 

    Route::resource('customers', CustomerController::class)
        ->only(['index', 'store', 'update', 'destroy']);

    Route::resource('suppliers', SupplierController::class)
        ->only(['index', 'store', 'update', 'destroy']);

    Route::resource('partners', PartnerController::class)
        ->only(['index', 'store', 'update', 'destroy']);

    Route::resource('fuel-products', FuelProductController::class)
        ->only(['index', 'store', 'update', 'destroy']);
 

});

require __DIR__.'/auth.php';
