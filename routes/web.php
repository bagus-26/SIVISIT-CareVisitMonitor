<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PatientController;
use App\Http\Controllers\Admin\MonitoringController;
use App\Http\Controllers\Admin\RekamMedisController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\SearchController;
use App\Http\Controllers\Admin\StaffController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\LocationMonitorController;


Route::get('/', function () {
    if (auth()->check()) {
        $user = auth()->user();
        if ($user->role === 'petugas') {
            return redirect()->route('admin.monitorings.index');
        }
        return redirect()->route('admin.dashboard');
    }
    return redirect()->route('login');
})->name('home');

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

    // Patient Routes — all roles can access (view & search only)
    Route::get('/admin/patients', [PatientController::class, 'index'])->name('admin.patients.index');
    Route::get('/admin/patients/{patient_id}/edit', [PatientController::class, 'edit'])->name('admin.patients.edit');
    Route::put('/admin/patients/{patient_id}', [PatientController::class, 'update'])->name('admin.patients.update');
    Route::get('/admin/search', [SearchController::class, 'index'])->name('admin.patients.search');

    // Monitoring Routes — all roles can access
    Route::get('/admin/monitorings', [MonitoringController::class, 'index'])->name('admin.monitorings.index');
    Route::get('/admin/monitorings/create', [MonitoringController::class, 'create'])->name('admin.monitorings.create');
    Route::post('/admin/monitorings', [MonitoringController::class, 'store'])->name('admin.monitorings.store');
    Route::get('/admin/monitorings/{id}', [MonitoringController::class, 'show'])->name('admin.monitorings.show');
    Route::get('/admin/monitorings/{id}/edit', [MonitoringController::class, 'edit'])->name('admin.monitorings.edit');
    Route::put('/admin/monitorings/{id}', [MonitoringController::class, 'update'])->name('admin.monitorings.update');
    Route::delete('/admin/monitorings/{id}', [MonitoringController::class, 'destroy'])->name('admin.monitorings.destroy');

    // Rekam Medis Routes — all roles can access
    Route::get('/admin/rekam-medis', [RekamMedisController::class, 'index'])->name('admin.rekam-medis.index');

    // Profile Routes — all roles can access
    Route::get('/admin/profil', [ProfileController::class, 'index'])->name('admin.profil');
    Route::put('/admin/profil', [ProfileController::class, 'update'])->name('admin.profil.update');

    // Admin-only routes
    Route::middleware('role:admin')->group(function () {
        // Patient Create & Delete (admin only)
        Route::get('/admin/patients/create', [PatientController::class, 'create'])->name('admin.patients.create');
        Route::post('/admin/patients', [PatientController::class, 'store'])->name('admin.patients.store');
        Route::delete('/admin/patients/{patient_id}', [PatientController::class, 'destroy'])->name('admin.patients.destroy');

        // Staff Routes
        Route::get('/admin/staff', [StaffController::class, 'index'])->name('admin.staff.index');
        Route::get('/admin/staff/create', [StaffController::class, 'create'])->name('admin.staff.create');
        Route::post('/admin/staff', [StaffController::class, 'store'])->name('admin.staff.store');
        Route::get('/admin/staff/{staff}/edit', [StaffController::class, 'edit'])->name('admin.staff.edit');
        Route::put('/admin/staff/{staff}', [StaffController::class, 'update'])->name('admin.staff.update');
        Route::delete('/admin/staff/{staff}', [StaffController::class, 'destroy'])->name('admin.staff.destroy');

        // Report Routes
        Route::get('/admin/reports', [ReportController::class, 'index'])->name('admin.reports.index');
        Route::get('/admin/reports/export-pdf', [ReportController::class, 'exportPdf'])->name('admin.reports.export-pdf');
        Route::get('/admin/reports/export-excel', [ReportController::class, 'exportExcel'])->name('admin.reports.export-excel');

        // Settings Routes
        Route::get('/admin/settings', [SettingController::class, 'index'])->name('admin.settings.index');
        Route::post('/admin/settings/profile', [SettingController::class, 'updateProfile'])->name('admin.settings.update-profile');
        Route::post('/admin/settings/password', [SettingController::class, 'changePassword'])->name('admin.settings.change-password');

        // Location Monitor Routes (admin only)
        Route::get('/admin/location', [LocationMonitorController::class, 'adminMap'])->name('admin.location.map');
        Route::get('/admin/location/petugas/{id}/patients', [LocationMonitorController::class, 'getPetugasPatients'])->name('admin.location.petugas-patients');
    });

    // Location Routes (all authenticated users)
    Route::get('/admin/location/saya', [LocationMonitorController::class, 'petugasTracker'])->name('admin.location.saya');
    Route::post('/admin/location/update', [LocationMonitorController::class, 'updateLocation'])->name('admin.location.update');

    // Reassign Patient (admin only via AJAX)
    Route::put('/admin/patients/{patient_id}/reassign', [PatientController::class, 'reassign'])->name('admin.patients.reassign')->middleware('role:admin');
});

// ─── Route Darurat untuk Clear Cache di InfinityFree (Tanpa Terminal) ───
Route::get('/clear-semua-cache', function() {
    \Artisan::call('route:clear');
    \Artisan::call('view:clear');
    \Artisan::call('config:clear');
    \Artisan::call('cache:clear');
    return "Semua cache di server berhasil dibersihkan! Silakan hapus kembali route ini demi keamanan.";
});


