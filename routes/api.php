<?php

use App\Http\Controllers\Api\PatientController;
use App\Http\Controllers\Api\MonitoringController;
use App\Http\Controllers\Api\AuthController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes — SIVISIT CareVisit Monitor
| Base URL: /api
|--------------------------------------------------------------------------
*/

// ── Auth
Route::post('/login',    [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

// ── Patients
Route::get('/patients',                      [PatientController::class, 'index']);
Route::post('/patients',                     [PatientController::class, 'store']);
Route::get('/patients/{id}',                 [PatientController::class, 'show']);
Route::put('/patients/{id}',                 [PatientController::class, 'update']);
Route::patch('/patients/{id}',               [PatientController::class, 'update']);
Route::delete('/patients/{id}',              [PatientController::class, 'destroy']);
Route::get('/patients/{id}/monitoring',      [PatientController::class, 'monitoring']);   // alias
Route::get('/patients/{id}/monitorings',     [PatientController::class, 'monitoring']);   // alias

// ── Alias Bahasa Indonesia (sesuai spesifikasi)
Route::get('/pasien',                        [PatientController::class, 'index']);
Route::get('/pasien/{id}/monitoring',        [PatientController::class, 'monitoring']);

// ── Monitorings
Route::get('/monitorings',                   [MonitoringController::class, 'index']);
Route::post('/monitorings',                  [MonitoringController::class, 'store']);
Route::post('/monitoring',                   [MonitoringController::class, 'store']);     // alias lama
Route::get('/monitorings/{id}',              [MonitoringController::class, 'show']);
Route::delete('/monitorings/{id}',           [MonitoringController::class, 'destroy']);
Route::get('/monitoring/status/{status}',    [MonitoringController::class, 'byStatus']);

// ── Preflight OPTIONS (handled by CorsMiddleware)
Route::options('/{any}', fn() => response('', 200))->where('any', '.*');
