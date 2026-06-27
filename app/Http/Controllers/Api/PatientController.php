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
        } elseif ($patientId = $request->query('patient_id')) {
            $query->where('patient_id', $patientId);
        } elseif ($nik = $request->query('nik')) {
            $query->where('nik_dummy', $nik);
        }

        $patients = $query->get();

        return response()->json([
            'success' => true,
            'message' => 'Daftar pasien berhasil diambil.',
            'data'    => $patients,
        ], 200);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_id'      => 'required|string|unique:patients,patient_id',
            'patient_name'    => 'required|string|max:255',
            'nik_dummy'       => 'nullable|string|size:16',
            'datebirth'       => 'required|date',
            'gender'          => 'required|in:Male,Female',
            'address'         => 'required|string',
            'latitude'        => 'nullable|numeric|between:-90,90',
            'longitude'       => 'nullable|numeric|between:-180,180',
            'family_phone'    => 'required|string',
            'patient_category'=> 'required|string',
        ]);

        $patient = Patient::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Pasien berhasil ditambahkan.',
            'data'    => $patient,
        ], 201);
    }

    public function update(Request $request, string $kode_pasien)
    {
        $patient = Patient::where('patient_id', $kode_pasien)->first();
        if (!$patient) {
            return response()->json([
                'success' => false,
                'message' => 'Pasien tidak ditemukan.',
            ], 404);
        }

        $validated = $request->validate([
            'patient_name'    => 'nullable|string|max:255',
            'nik_dummy'       => 'nullable|string|size:16',
            'datebirth'       => 'nullable|date',
            'gender'          => 'nullable|in:Male,Female',
            'address'         => 'nullable|string',
            'latitude'        => 'nullable|numeric|between:-90,90',
            'longitude'       => 'nullable|numeric|between:-180,180',
            'family_phone'    => 'nullable|string',
            'patient_category'=> 'nullable|string',
        ]);

        $patient->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Pasien berhasil diperbarui.',
            'data'    => $patient,
        ], 200);
    }

    public function destroy(string $kode_pasien)
    {
        $patient = Patient::where('patient_id', $kode_pasien)->first();
        if (!$patient) {
            return response()->json([
                'success' => false,
                'message' => 'Pasien tidak ditemukan.',
            ], 404);
        }

        $patient->monitorings()->delete();
        $patient->delete();

        return response()->json([
            'success' => true,
            'message' => 'Pasien beserta data monitoring berhasil dihapus.',
        ], 200);
    }

    public function monitoring(string $kode_pasien)
    {
        $patient = Patient::with([
            'monitorings' => fn($q) => $q->with('user')->latest('monitoring_date')->latest('monitoring_time')
        ])->where('patient_id', $kode_pasien)
          ->orWhere('nik_dummy', $kode_pasien)
          ->first();

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
