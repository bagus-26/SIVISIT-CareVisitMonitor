<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePatientRequest;
use App\Models\Patient;
use App\Models\Monitoring;
use Illuminate\Http\Request;

class PatientController extends Controller
{
    /**
     * GET /api/patients — semua pasien + monitoring terbaru
     */
    public function index(Request $request)
    {
        $query = Patient::with([
            'monitorings' => fn($q) => $q->latest('monitoring_date')->latest('monitoring_time')
        ]);

        // Support pencarian by q (name, nik, patient_id)
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

    /**
     * POST /api/patients — tambah pasien baru
     */
    public function store(StorePatientRequest $request)
    {
        $patient = Patient::create($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Pasien berhasil ditambahkan.',
            'data'    => $patient,
        ], 201);
    }

    /**
     * GET /api/patients/{id} — detail pasien + semua monitoring
     */
    public function show(string $id)
    {
        $patient = Patient::with([
            'monitorings' => fn($q) => $q->with('user')->latest('monitoring_date')->latest('monitoring_time')
        ])->where('patient_id', $id)->first();

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

    /**
     * GET /api/patients/{id}/monitoring — riwayat monitoring pasien (alias show)
     */
    public function monitoring(string $id)
    {
        return $this->show($id);
    }

    /**
     * PUT/PATCH /api/patients/{id} — update data pasien
     */
    public function update(StorePatientRequest $request, string $id)
    {
        $patient = Patient::where('patient_id', $id)->first();

        if (!$patient) {
            return response()->json([
                'success' => false,
                'message' => 'Pasien tidak ditemukan.',
            ], 404);
        }

        $patient->update($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Data pasien berhasil diperbarui.',
            'data'    => $patient->fresh(),
        ], 200);
    }

    /**
     * DELETE /api/patients/{id} — hapus pasien + semua monitoring-nya
     */
    public function destroy(string $id)
    {
        $patient = Patient::where('patient_id', $id)->first();

        if (!$patient) {
            return response()->json([
                'success' => false,
                'message' => 'Pasien tidak ditemukan.',
            ], 404);
        }

        // Hapus monitoring terkait dulu
        Monitoring::where('patient_id', $id)->delete();
        $patient->delete();

        return response()->json([
            'success' => true,
            'message' => 'Pasien dan seluruh data monitoring berhasil dihapus.',
        ], 200);
    }
}
