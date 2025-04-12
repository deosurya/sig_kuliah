<?php

use App\Http\Controllers\MarkerController;
use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin'       => Route::has('login'),
        'canRegister'    => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion'     => PHP_VERSION,
    ]);
})->name('welcome');

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware('auth')->group(function () {
    Route::get('/addmarker', function () {
        return Inertia::render('Admin/AddMarker');
    })->name('addmarker');
});

Route::prefix('api')->middleware('auth')->group(function () {
    Route::post('/markers', [MarkerController::class, 'store']);
    Route::delete('/markers/{marker}', [MarkerController::class, 'destroy']);
});

Route::prefix('api')->group(function () {
    Route::get('/markers', [MarkerController::class, 'index']);
});

require __DIR__ . '/auth.php';
