<?php

use App\Http\Controllers\Api\PatientController;
use App\Http\Controllers\Api\MonitoringController;
use App\Http\Controllers\Api\AuthController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes — SIVISIT-CareVisitMonitor
| Base URL: /api
|--------------------------------------------------------------------------
*/

// ── Auth (Public)
Route::post('/login',    [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/email/resend', [AuthController::class, 'resendVerification']);

// ── Email Verification (Signed URL — public)
Route::get('/email/verify/{id}/{hash}', [AuthController::class, 'verify'])
    ->middleware(['signed'])
    ->name('verification.verify');

// ── Protected Routes (require Sanctum token)
Route::middleware('auth:sanctum')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout']);

    // ── Patients
    Route::get('/patients',                      [PatientController::class, 'index']);
    Route::post('/patients',                     [PatientController::class, 'store']);
    Route::get('/patients/{id}',                 [PatientController::class, 'show']);
    Route::put('/patients/{id}',                 [PatientController::class, 'update']);
    Route::patch('/patients/{id}',               [PatientController::class, 'update']);
    Route::delete('/patients/{id}',              [PatientController::class, 'destroy']);
    Route::get('/patients/{id}/monitoring',      [PatientController::class, 'monitoring']);
    Route::get('/patients/{id}/monitorings',     [PatientController::class, 'monitoring']);

    // ── Alias Bahasa Indonesia
    Route::get('/pasien',                        [PatientController::class, 'index']);
    Route::get('/pasien/{id}/monitoring',        [PatientController::class, 'monitoring']);

    // ── Monitorings
    Route::get('/monitorings',                   [MonitoringController::class, 'index']);
    Route::post('/monitorings',                  [MonitoringController::class, 'store']);
    Route::post('/monitoring',                   [MonitoringController::class, 'store']);
    Route::get('/monitorings/{id}',              [MonitoringController::class, 'show']);
    Route::delete('/monitorings/{id}',           [MonitoringController::class, 'destroy']);
    Route::get('/monitoring/status/{status}',    [MonitoringController::class, 'byStatus']);
});

// ── Preflight OPTIONS (handled by CorsMiddleware)
Route::options('/{any}', fn() => response('', 200))->where('any', '.*');
