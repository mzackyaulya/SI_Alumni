<?php

use App\Http\Controllers\AlumniController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('/dashboard');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

Route::get('/alumni/biodata', [AlumniController::class, 'biodata'])->name('alumni.biodata');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Start Router Alumni

Route::middleware(['auth','role:admin'])->group(function () {
    Route::get('/alumni/create',        [AlumniController::class, 'create'])->name('alumni.create');
    Route::post('/alumni',              [AlumniController::class, 'store'])->name('alumni.store');
    Route::get('/alumni/{alumni}/edit', [AlumniController::class, 'edit'])->name('alumni.edit');
    Route::put('/alumni/{alumni}',      [AlumniController::class, 'update'])->name('alumni.update');
    Route::delete('/alumni/{alumni}',   [AlumniController::class, 'destroy'])->name('alumni.destroy');
});

Route::middleware(['auth','role:admin,alumni'])->group(function () {
    Route::get('/alumni', [AlumniController::class, 'index'])->name('alumni.index');
    Route::get('/alumni/{alumni}', [AlumniController::class, 'show'])->name('alumni.show');
    Route::get('/alumni/{alumni}/edit', [AlumniController::class, 'edit'])->name('alumni.edit');
    Route::put('/alumni/{alumni}',      [AlumniController::class, 'update'])->name('alumni.update');
});

// End Router Alumni
require __DIR__.'/auth.php';
