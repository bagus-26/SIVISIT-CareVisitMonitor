<?php

use App\Http\Controllers\Api\PatientController;
use App\Http\Controllers\Api\MonitoringController;
use Illuminate\Support\Facades\Route;

Route::get('/pasien',                       [PatientController::class, 'index']);
Route::get('/pasien/{kode_pasien}/monitoring', [PatientController::class, 'monitoring']);
Route::post('/monitoring',                  [MonitoringController::class, 'store']);
Route::get('/monitoring/status/{status}',   [MonitoringController::class, 'byStatus']);

Route::options('/{any}', fn() => response('', 200))->where('any', '.*');
