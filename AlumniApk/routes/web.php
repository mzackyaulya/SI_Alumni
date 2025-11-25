<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EventController;
use App\Http\Controllers\AlumniController;
use App\Http\Controllers\LamaranController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LowonganController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PerusahaanController;
use App\Http\Controllers\QuestionnaireAdminController;
use App\Http\Controllers\QuestionnaireAnswerController;

/*
|--------------------------------------------------------------------------
| ROUTE PUBLIK (BISA DIAKSES TANPA LOGIN)
| Alumni yang sudah login akan dicek kuesionernya oleh middleware
| questionnaire.completed
|--------------------------------------------------------------------------
*/

// Dashboard
Route::get('/', [DashboardController::class, 'index'])
    ->name('dashboard')
    ->middleware('questionnaire.completed');

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->name('dashboard')
    ->middleware('questionnaire.completed');

// Profil sekolah (static pages)
Route::view('/visi-misi', 'visi-misi')
    ->middleware('questionnaire.completed');

Route::view('/sejarah', 'sejarah')
    ->middleware('questionnaire.completed');

Route::view('/struktur-organisasi', 'struktur-organisasi')
    ->middleware('questionnaire.completed');

// Biodata alumni (grid) – publik
Route::get('/alumni/biodata', [AlumniController::class, 'biodata'])
    ->name('alumni.biodata')
    ->middleware('questionnaire.completed');

// Lowongan kerja – publik
Route::get('/lowongan', [LowonganController::class, 'index'])
    ->name('lowongan.index')
    ->middleware('questionnaire.completed');

Route::get('/lowongan/{lowongan}', [LowonganController::class, 'show'])
    ->name('lowongan.show')
    ->middleware('questionnaire.completed');

// Event – publik
Route::get('/event', [EventController::class, 'index'])
    ->name('event.index')
    ->middleware('questionnaire.completed');

Route::get('/events/{event:slug}', [EventController::class, 'show'])
    ->name('events.show')
    ->middleware('questionnaire.completed');


/*
|--------------------------------------------------------------------------
| ROUTE AUTH UMUM (PROFILE USER)
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
    Route::get('/profile/edit', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::get('/profile', function () {
        return view('profile.show');
    })->name('profile.show');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');
});


/*
|--------------------------------------------------------------------------
| ROUTE ALUMNI (ADMIN / WAKA – CRUD DATA ALUMNI)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:admin,waka'])->group(function () {
    Route::get('/alumni/export', [AlumniController::class, 'export'])
        ->name('alumni.export');

    Route::get('/alumni/create', [AlumniController::class, 'create'])
        ->name('alumni.create');

    Route::post('/alumni', [AlumniController::class, 'store'])
        ->name('alumni.store');

    Route::get('/alumni/{alumni}/edit', [AlumniController::class, 'edit'])
        ->name('alumni.edit');

    Route::put('/alumni/{alumni}', [AlumniController::class, 'update'])
        ->name('alumni.update');

    Route::delete('/alumni/{alumni}', [AlumniController::class, 'destroy'])
        ->name('alumni.destroy');
});

// daftar & detail alumni (versi login, untuk admin/alumni/waka)
Route::middleware(['auth', 'role:admin,alumni,waka'])->group(function () {
    Route::get('/alumni', [AlumniController::class, 'index'])
        ->name('alumni.index');

    Route::get('/alumni/{alumni}', [AlumniController::class, 'show'])
        ->name('alumni.show')->middleware('questionnaire.completed');
});


/*
|--------------------------------------------------------------------------
| ROUTE PERUSAHAAN / COMPANY
|--------------------------------------------------------------------------
*/

// Perusahaan melihat lamaran yang masuk
Route::middleware(['auth', 'role:company'])->group(function () {
    Route::get('/perusahaan/lamaran', [LamaranController::class, 'companyIndex'])
        ->name('perusahaan.lamaran.index');

    Route::patch('/lamaran/{lamaran}/status', [LamaranController::class, 'updateStatus'])
        ->name('lamaran.updateStatus');
});

// Biodata perusahaan – hanya bisa dilihat jika login (semua role yang login)
Route::middleware(['auth'])->group(function () {
    Route::get('/perusahaan/biodata', [PerusahaanController::class, 'biodataIndex'])
        ->name('perusahaan.biodata.index');

    Route::get('/perusahaan/biodata/{perusahaan}', [PerusahaanController::class, 'biodataShow'])
        ->name('perusahaan.biodata.show');
});

// CRUD perusahaan khusus admin
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/perusahaan/create', [PerusahaanController::class, 'create'])
        ->name('perusahaan.create');

    Route::post('/perusahaan', [PerusahaanController::class, 'store'])
        ->name('perusahaan.store');

    Route::get('/perusahaan/{perusahaan}/edit', [PerusahaanController::class, 'edit'])
        ->name('perusahaan.edit');

    Route::put('/perusahaan/{perusahaan}', [PerusahaanController::class, 'update'])
        ->name('perusahaan.update');

    Route::delete('/perusahaan/{perusahaan}', [PerusahaanController::class, 'destroy'])
        ->name('perusahaan.destroy');

    Route::get('/perusahaan', [PerusahaanController::class, 'index'])
        ->name('perusahaan.index');

    Route::get('/perusahaan/{perusahaan}', [PerusahaanController::class, 'show'])
        ->name('perusahaan.show');

    Route::patch('/perusahaan/{perusahaan}/verify', [PerusahaanController::class, 'verifyToggle'])
        ->name('perusahaan.verify');
});

// beberapa aksi perusahaan boleh untuk admin & company
Route::middleware(['auth', 'role:admin,company'])->group(function () {
    Route::get('/perusahaan/{perusahaan}/edit', [PerusahaanController::class, 'edit'])
        ->name('perusahaan.edit');

    Route::put('/perusahaan/{perusahaan}', [PerusahaanController::class, 'update'])
        ->name('perusahaan.update');

    Route::get('/perusahaan', [PerusahaanController::class, 'index'])
        ->name('perusahaan.index');

    Route::patch('/perusahaan/{perusahaan}/verify', [PerusahaanController::class, 'verifyToggle'])
        ->name('perusahaan.verify');
});


/*
|--------------------------------------------------------------------------
| ROUTE LOWONGAN (CRUD – HARUS LOGIN)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {
    Route::get('/lowongan/create', [LowonganController::class, 'create'])
        ->name('lowongan.create');

    Route::post('/lowongan', [LowonganController::class, 'store'])
        ->name('lowongan.store');

    Route::get('/lowongan/{lowongan}/edit', [LowonganController::class, 'edit'])
        ->name('lowongan.edit');

    Route::put('/lowongan/{lowongan}', [LowonganController::class, 'update'])
        ->name('lowongan.update');

    Route::delete('/lowongan/{lowongan}', [LowonganController::class, 'destroy'])
        ->name('lowongan.destroy');
});


/*
|--------------------------------------------------------------------------
| ROUTE LAMARAN (KHUSUS ALUMNI)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:alumni'])->group(function () {
    Route::get('/lamaran', [LamaranController::class, 'index'])
        ->name('lamaran.index');

    Route::get('/lamaran/create/{lowongan}', [LamaranController::class, 'create'])
        ->name('lamaran.create');

    Route::post('/lamaran/{lowongan}', [LamaranController::class, 'store'])
        ->name('lamaran.store');

    Route::patch('/lamaran/{lamaran}/withdraw', [LamaranController::class, 'withdraw'])
        ->name('lamaran.withdraw');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/lamaran/{lamaran}', [LamaranController::class, 'show'])
        ->name('lamaran.show');
});


/*
|--------------------------------------------------------------------------
| ROUTE EVENT (ADMIN PANEL)
|--------------------------------------------------------------------------
*/

Route::prefix('admin')->name('admin.')->middleware(['auth'])->group(function () {
    Route::get('/event', [EventController::class, 'adminIndex'])
        ->name('event.index');

    Route::get('/event/create', [EventController::class, 'create'])
        ->name('event.create');

    Route::post('/event', [EventController::class, 'store'])
        ->name('event.store');

    Route::get('/event/{event:slug}/edit', [EventController::class, 'edit'])
        ->name('event.edit');

    Route::put('/event/{event:slug}', [EventController::class, 'update'])
        ->name('event.update');

    Route::delete('/event/{event:slug}', [EventController::class, 'destroy'])
        ->name('event.destroy');

    Route::patch('/event/{event:slug}/toggle', [EventController::class, 'togglePublish'])
        ->name('event.toggle');
});


/*
|--------------------------------------------------------------------------
| ROUTE WAKA KESISWAAN
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:waka'])->prefix('waka')->name('waka.')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'wakaDashboard'])
        ->name('dashboard');

    Route::get('/alumni', [AlumniController::class, 'index'])
        ->name('alumni.index');

    Route::get('/alumni/{alumni}/edit', [AlumniController::class, 'edit'])
        ->name('alumni.edit');

    Route::put('/alumni/{alumni}', [AlumniController::class, 'update'])
        ->name('alumni.update');

    Route::delete('/alumni/{alumni}', [AlumniController::class, 'destroy'])
        ->name('alumni.destroy');

    Route::get('/alumni/export/pdf', [AlumniController::class, 'exportPdf'])
        ->name('alumni.export.pdf');

    Route::get('/alumni/export/excel', [AlumniController::class, 'exportExcel'])
        ->name('alumni.export.excel');

    Route::resource('event', EventController::class);
    Route::resource('lowongan', LowonganController::class);

    Route::get('/status-lamaran', [LamaranController::class, 'statusForWaka'])
        ->name('status.lamaran');
});


/*
|--------------------------------------------------------------------------
| ROUTE KUESIONER (ADMIN & ALUMNI)
|--------------------------------------------------------------------------
*/

// Admin & waka kelola kuesioner
Route::middleware(['auth', 'role:admin,waka'])->group(function () {

    Route::get('/admin/questionnaire', [QuestionnaireAdminController::class, 'index'])
        ->name('admin.questionnaire.index');

    Route::get('/admin/questionnaire/create', [QuestionnaireAdminController::class, 'create'])
        ->name('admin.questionnaire.create');

    Route::post('/admin/questionnaire', [QuestionnaireAdminController::class, 'store'])
        ->name('admin.questionnaire.store');

    Route::get('/admin/questionnaire/{id}/edit', [QuestionnaireAdminController::class, 'edit'])
        ->name('admin.questionnaire.edit');

    Route::put('/admin/questionnaire/{id}', [QuestionnaireAdminController::class, 'update'])
        ->name('admin.questionnaire.update');

    Route::delete('/admin/questionnaire/{id}', [QuestionnaireAdminController::class, 'destroy'])
        ->name('admin.questionnaire.destroy');

    Route::post('/admin/questionnaire/{id}/questions', [QuestionnaireAdminController::class, 'storeQuestion'])
        ->name('admin.questionnaire.questions.store');

    Route::put('/admin/questions/{id}', [QuestionnaireAdminController::class, 'updateQuestion'])
        ->name('admin.questionnaire.questions.update');

    Route::delete('/admin/questions/{id}', [QuestionnaireAdminController::class, 'destroyQuestion'])
        ->name('admin.questionnaire.questions.destroy');

    Route::get('/admin/questionnaire/{id}/results', [QuestionnaireAdminController::class, 'results'])
        ->name('admin.questionnaire.results');
});

// Alumni isi kuesioner
Route::middleware(['auth', 'role:alumni'])->group(function () {
    Route::get('/questionnaire/{id}/fill', [QuestionnaireAnswerController::class, 'fill'])
        ->name('questionnaire.fill');

    Route::post('/questionnaire/{id}/submit', [QuestionnaireAnswerController::class, 'submit'])
        ->name('questionnaire.submit');
});

require __DIR__.'/auth.php';
