<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\FuelProductController;
use App\Http\Controllers\PartnerController;
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

 

    Route::get('/customers', [CustomerController::class, 'index']);
    

    Route::get('/suppliers', [SupplierController::class, 'index']);

    Route::get('/partners', [PartnerController::class, 'index']);

    Route::get('/fuel-products', [FuelProductController::class, 'index']);
 

});

require __DIR__.'/auth.php';
