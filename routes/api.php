<?php

use App\Http\Controllers\Api\PatientController;
use App\Http\Controllers\Api\MonitoringController;
use App\Http\Controllers\Api\AuthController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::get('/patients', [PatientController::class, 'index']);
Route::post('/patients', [PatientController::class, 'store']);
Route::get('/patients/{id}/monitoring', [PatientController::class, 'show']);

Route::get('/monitorings', [MonitoringController::class, 'index']);
Route::post('/monitoring', [MonitoringController::class, 'store']);

