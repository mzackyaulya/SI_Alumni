<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AlumniController;
use App\Http\Controllers\LamaranController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LowonganController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\PerusahaanController;

// Route::get('/', function () {
//     return view('/dashboard');
// });
Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

Route::view('/visi-misi', 'visi-misi');
Route::view('/sejarah', 'sejarah');
Route::view('/struktur-organisasi', 'struktur-organisasi');

Route::get('/alumni/biodata', [AlumniController::class, 'biodata'])->name('alumni.biodata');

Route::middleware('auth')->group(function () {
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::get('/profile', function () {
        return view('profile.show');
    })->name('profile.show');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Start Router Alumni

Route::middleware(['auth','role:admin,waka'])->group(function () {
    Route::get('/alumni/export', [AlumniController::class, 'export'])->name('alumni.export')->middleware('auth');
    Route::get('/alumni/create',        [AlumniController::class, 'create'])->name('alumni.create');
    Route::post('/alumni',              [AlumniController::class, 'store'])->name('alumni.store');
    Route::get('/alumni/{alumni}/edit', [AlumniController::class, 'edit'])->name('alumni.edit');
    Route::put('/alumni/{alumni}',      [AlumniController::class, 'update'])->name('alumni.update');
    Route::delete('/alumni/{alumni}',   [AlumniController::class, 'destroy'])->name('alumni.destroy');
});


Route::middleware(['auth','role:admin,alumni,waka'])->group(function () {
    Route::get('/alumni', [AlumniController::class, 'index'])->name('alumni.index');
    Route::get('/alumni/{alumni}', [AlumniController::class, 'show'])->name('alumni.show');
    Route::get('/alumni/{alumni}/edit', [AlumniController::class, 'edit'])->name('alumni.edit');
    Route::put('/alumni/{alumni}',      [AlumniController::class, 'update'])->name('alumni.update');
});

// End Router Alumni

Route::middleware(['auth', 'role:company'])->group(function () {
    // daftar semua lamaran yg masuk ke lowongan milik perusahaan ini
    Route::get('/perusahaan/lamaran', [LamaranController::class, 'companyIndex'])
        ->name('perusahaan.lamaran.index');

    // update status lamaran (review, interview, accepted, dll)
    Route::patch('/lamaran/{lamaran}/status', [LamaranController::class, 'updateStatus'])
        ->name('lamaran.updateStatus');
});


Route::middleware(['auth'])->group(function () {
    // daftar semua biodata perusahaan (grid) — bisa dilihat semua role yang login
    Route::get('/perusahaan/biodata', [PerusahaanController::class, 'biodataIndex'])
        ->name('perusahaan.biodata.index');

    // (opsional) detail satu perusahaan dari halaman biodata
    Route::get('/perusahaan/biodata/{perusahaan}', [PerusahaanController::class, 'biodataShow'])
        ->name('perusahaan.biodata.show');
});

Route::middleware(['auth','role:admin'])->group(function () {
    Route::get('/perusahaan/create',        [PerusahaanController::class, 'create'])->name('perusahaan.create');
    Route::post('/perusahaan',              [PerusahaanController::class, 'store'])->name('perusahaan.store');
    Route::get('/perusahaan/{perusahaan}/edit', [PerusahaanController::class, 'edit'])->name('perusahaan.edit');
    Route::put('/perusahaan/{perusahaan}',      [PerusahaanController::class, 'update'])->name('perusahaan.update');
    Route::delete('/perusahaan/{perusahaan}',   [PerusahaanController::class, 'destroy'])->name('perusahaan.destroy');
    Route::get('/perusahaan', [PerusahaanController::class, 'index'])->name('perusahaan.index');
    Route::get('/perusahaan/{perusahaan}', [PerusahaanController::class, 'show'])->name('perusahaan.show');
    Route::patch('/perusahaan/{perusahaan}/verify', [PerusahaanController::class, 'verifyToggle'])->name('perusahaan.verify');
});

Route::middleware(['auth','role:admin,company'])->group(function () {
    Route::get('/perusahaan/{perusahaan}/edit', [PerusahaanController::class, 'edit'])->name('perusahaan.edit');
    Route::put('/perusahaan/{perusahaan}',      [PerusahaanController::class, 'update'])->name('perusahaan.update');
    Route::get('/perusahaan', [PerusahaanController::class, 'index'])->name('perusahaan.index');
    Route::patch('/perusahaan/{perusahaan}/verify', [PerusahaanController::class, 'verifyToggle'])->name('perusahaan.verify');
});

Route::get('/lowongan', [LowonganController::class, 'index'])->name('lowongan.index');

// Aksi perusahaan/admin — butuh login
Route::middleware(['auth'])->group(function () {
    Route::get('/lowongan/create', [LowonganController::class, 'create'])->name('lowongan.create');
    Route::post('/lowongan', [LowonganController::class, 'store'])->name('lowongan.store');

    Route::get('/lowongan/{lowongan}/edit', [LowonganController::class, 'edit'])->name('lowongan.edit');
    Route::put('/lowongan/{lowongan}', [LowonganController::class, 'update'])->name('lowongan.update');
    Route::delete('/lowongan/{lowongan}', [LowonganController::class, 'destroy'])->name('lowongan.destroy');
});

Route::get('/lowongan/{lowongan}', [LowonganController::class, 'show'])->name('lowongan.show');

Route::middleware(['auth', 'role:alumni'])->group(function () {
    // riwayat lamaran
    Route::get('/lamaran', [LamaranController::class, 'index'])->name('lamaran.index');

    // form lamar
    Route::get('/lamaran/create/{lowongan}', [LamaranController::class, 'create'])
        ->name('lamaran.create');

    // simpan lamaran (PERHATIKAN: ada {lowongan})
    Route::post('/lamaran/{lowongan}', [LamaranController::class, 'store'])
        ->name('lamaran.store');

    Route::patch('/lamaran/{lamaran}/withdraw', [LamaranController::class, 'withdraw'])
        ->name('lamaran.withdraw');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/lamaran/{lamaran}', [LamaranController::class, 'show'])
        ->name('lamaran.show');
});



Route::get('/event', [EventController::class, 'index'])->name('event.index');
Route::get('/events/{event:slug}', [EventController::class, 'show'])->name('events.show');

Route::prefix('admin')->name('admin.')->middleware(['auth'])->group(function () {
    Route::get('/event', [EventController::class, 'adminIndex'])->name('event.index');
    Route::get('/event/create', [EventController::class, 'create'])->name('event.create');
    Route::post('/event', [EventController::class, 'store'])->name('event.store');
    Route::get('/event/{event:slug}/edit', [EventController::class, 'edit'])->name('event.edit');
    Route::put('/event/{event:slug}', [EventController::class, 'update'])->name('event.update');
    Route::delete('/event/{event:slug}', [EventController::class, 'destroy'])->name('event.destroy');
    Route::patch('/event/{event:slug}/toggle', [EventController::class, 'togglePublish'])->name('event.toggle');
});

Route::middleware(['auth','role:waka'])->prefix('waka')->name('waka.')->group(function () {

    // Dashboard Waka
    Route::get('/dashboard', [DashboardController::class, 'wakaDashboard'])
        ->name('dashboard');

    // Data Alumni
    Route::get('/alumni', [AlumniController::class, 'index'])->name('alumni.index');
    Route::get('/alumni/{alumni}/edit', [AlumniController::class, 'edit'])->name('alumni.edit');
    Route::put('/alumni/{alumni}', [AlumniController::class, 'update'])->name('alumni.update');
    Route::delete('/alumni/{alumni}', [AlumniController::class, 'destroy'])->name('alumni.destroy');
    Route::get('/alumni/export/pdf', [AlumniController::class, 'exportPdf'])->name('alumni.export.pdf');
    Route::get('/alumni/export/excel', [AlumniController::class, 'exportExcel'])->name('alumni.export.excel');

    // Event
    Route::resource('event', EventController::class);

    // Lowongan kerja
    Route::resource('lowongan', LowonganController::class);

    // Status alumni (diterima/tidak)
    Route::get('/status-lamaran', [LamaranController::class, 'statusForWaka'])
        ->name('status.lamaran');
});


require __DIR__.'/auth.php';
