<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use Illuminate\Http\Request;

class PatientController extends Controller
{
    public function index(Request $request)
    {
        $query = Patient::with([
            'monitorings' => fn($q) => $q->latest('monitoring_date')->latest('monitoring_time')
        ]);

        if ($search = $request->query('q')) {
            $q = '%' . $search . '%';
            $query->where(function ($w) use ($q) {
                $w->where('patient_name', 'LIKE', $q)
                  ->orWhere('nik_dummy', 'LIKE', $q)
                  ->orWhere('patient_id', 'LIKE', $q);
            });
        }

        $patients = $query->get();

        return response()->json([
            'success' => true,
            'message' => 'Daftar pasien berhasil diambil.',
            'data'    => $patients,
        ], 200);
    }

    public function monitoring(string $kode_pasien)
    {
        $patient = Patient::with([
            'monitorings' => fn($q) => $q->with('user')->latest('monitoring_date')->latest('monitoring_time')
        ])->where('patient_id', $kode_pasien)->first();

        if (!$patient) {
            return response()->json([
                'success' => false,
                'message' => 'Pasien tidak ditemukan.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Detail pasien berhasil diambil.',
            'data'    => $patient,
        ], 200);
    }
}
