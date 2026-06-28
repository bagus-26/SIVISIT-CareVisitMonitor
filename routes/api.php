<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PatientController;
use App\Http\Controllers\Api\MonitoringController;
use App\Http\Controllers\Api\LocationController;
use App\Http\Controllers\Api\ScheduleController;
use Illuminate\Support\Facades\Route;

// ─── Auth Routes ──────────────────────────────────────
Route::post('/login',       [AuthController::class, 'login']);
Route::post('/register',    [AuthController::class, 'register']);
Route::post('/logout',      [AuthController::class, 'logout'])->middleware('auth:sanctum');

// ─── Public Routes (Bisa diakses tanpa login oleh keluarga) ───
Route::get('/pasien/{kode_pasien}/monitoring', [PatientController::class, 'monitoring']);

// ─── Protected Routes (Harus Login / Menggunakan Bearer Token) ───
Route::middleware('auth:sanctum')->group(function () {
    // Patient Routes
    Route::get('/pasien',                       [PatientController::class, 'index']);
    Route::post('/pasien',                      [PatientController::class, 'store']);
    Route::put('/pasien/{kode_pasien}',         [PatientController::class, 'update']);
    Route::delete('/pasien/{kode_pasien}',      [PatientController::class, 'destroy']);

    // Monitoring Routes
    Route::post('/monitoring',                  [MonitoringController::class, 'store']);
    Route::get('/monitoring',                   [MonitoringController::class, 'index']);
    Route::get('/monitoring/status/{status}',   [MonitoringController::class, 'byStatus']);
    Route::get('/monitoring/{id}',              [MonitoringController::class, 'show']);
    Route::put('/monitoring/{id}',              [MonitoringController::class, 'update']);
    Route::delete('/monitoring/{id}',           [MonitoringController::class, 'destroy']);

    // Jadwal Kunjungan Routes
    Route::get('/jadwal',                       [ScheduleController::class, 'index']);
    Route::post('/jadwal',                      [ScheduleController::class, 'store']);
    Route::get('/jadwal/{id}',                  [ScheduleController::class, 'show']);
    Route::put('/jadwal/{id}',                  [ScheduleController::class, 'update']);
    Route::delete('/jadwal/{id}',               [ScheduleController::class, 'destroy']);

    // Location Routes
    Route::post('/location/update',          [LocationController::class, 'update']);
    Route::get('/location/petugas',          [LocationController::class, 'petugas']);
    Route::get('/location/history',          [LocationController::class, 'history']);
    Route::get('/location/nearby',           [LocationController::class, 'nearby']);
    Route::post('/location/geocode',         [LocationController::class, 'geocode']);
});

Route::options('/{any}', fn() => response('', 200))->where('any', '.*');
