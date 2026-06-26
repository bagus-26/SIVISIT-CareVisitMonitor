<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PatientController;
use App\Http\Controllers\Api\MonitoringController;
use App\Http\Controllers\Api\LocationController;
use Illuminate\Support\Facades\Route;

// ─── Auth Routes ──────────────────────────────────────
Route::post('/login',       [AuthController::class, 'login']);
Route::post('/register',    [AuthController::class, 'register']);
Route::post('/logout',      [AuthController::class, 'logout'])->middleware('auth:sanctum');

// ─── Patient Routes ───────────────────────────────────
Route::get('/pasien',                       [PatientController::class, 'index']);
Route::get('/pasien/{kode_pasien}/monitoring', [PatientController::class, 'monitoring']);
Route::post('/pasien',                      [PatientController::class, 'store']);
Route::put('/pasien/{kode_pasien}',         [PatientController::class, 'update']);
Route::delete('/pasien/{kode_pasien}',      [PatientController::class, 'destroy']);

// ─── Monitoring Routes ────────────────────────────────
Route::post('/monitoring',                  [MonitoringController::class, 'store']);
Route::get('/monitoring',                   [MonitoringController::class, 'index']);
Route::get('/monitoring/status/{status}',   [MonitoringController::class, 'byStatus']);

// ─── Location Routes ──────────────────────────────────
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/location/update',          [LocationController::class, 'update']);
    Route::get('/location/petugas',          [LocationController::class, 'petugas']);
    Route::get('/location/history',          [LocationController::class, 'history']);
    Route::get('/location/nearby',           [LocationController::class, 'nearby']);
    Route::post('/location/geocode',         [LocationController::class, 'geocode']);
});

Route::options('/{any}', fn() => response('', 200))->where('any', '.*');
