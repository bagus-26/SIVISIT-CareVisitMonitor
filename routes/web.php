<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PatientController;
use App\Http\Controllers\Admin\MonitoringController;
use App\Http\Controllers\Admin\RekamMedisController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\SearchController;


Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('admin.dashboard');
    }
    return view('welcome');
})->name('home');

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

    // Patient Routes
    Route::get('/admin/patients', [PatientController::class, 'index'])->name('admin.patients.index');
    Route::get('/admin/patients/create', [PatientController::class, 'create'])->name('admin.patients.create');
    Route::post('/admin/patients', [PatientController::class, 'store'])->name('admin.patients.store');
    Route::get('/admin/patients/{patient_id}/edit', [PatientController::class, 'edit'])->name('admin.patients.edit');
    Route::put('/admin/patients/{patient_id}', [PatientController::class, 'update'])->name('admin.patients.update');
    Route::delete('/admin/patients/{patient_id}', [PatientController::class, 'destroy'])->name('admin.patients.destroy');
    Route::get('/admin/search', [SearchController::class, 'index'])->name('admin.patients.search');

    // Monitoring Routes
    Route::get('/admin/monitorings', [MonitoringController::class, 'index'])->name('admin.monitorings.index');
    Route::get('/admin/monitorings/create', [MonitoringController::class, 'create'])->name('admin.monitorings.create');
    Route::post('/admin/monitorings', [MonitoringController::class, 'store'])->name('admin.monitorings.store');
    Route::get('/admin/monitorings/{id}', [MonitoringController::class, 'show'])->name('admin.monitorings.show');

    // Rekam Medis Routes
    Route::get('/admin/rekam-medis', [RekamMedisController::class, 'index'])->name('admin.rekam-medis.index');

    // Profile Routes
    Route::get('/admin/profil', [ProfileController::class, 'index'])->name('admin.profil');
    Route::put('/admin/profil', [ProfileController::class, 'update'])->name('admin.profil.update');

});


