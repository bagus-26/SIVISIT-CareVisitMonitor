<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Monitoring;
use Illuminate\Http\Request;

class MonitoringController extends Controller
{
    public function index(Request $request)
    {
        $query = Monitoring::with([
            'patient',
            'user' => fn($q) => $q->select('id', 'name', 'email'),
        ])->latest('monitoring_date')->latest('monitoring_time');

        if ($patientId = $request->query('patient_id')) {
            $query->where('patient_id', $patientId);
        }

        $monitorings = $query->get();

        return response()->json([
            'success' => true,
            'message' => 'Data monitoring berhasil diambil.',
            'data'    => $monitorings,
        ], 200);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_id'        => 'required|exists:patients,patient_id',
            'user_id'           => 'required|exists:users,id',
            'monitoring_date'   => 'required|date',
            'monitoring_time'   => 'nullable|string',
            'blood_pressure'    => ['required', 'regex:/^\d{2,3}\/\d{2,3}$/', function ($attr, $value, $fail) {
                $parts = explode('/', $value);
                $systolic = (int) $parts[0];
                $diastolic = (int) $parts[1];
                if ($systolic < 60 || $systolic > 250) $fail('Tekanan sistolik harus antara 60-250 mmHg.');
                if ($diastolic < 40 || $diastolic > 150) $fail('Tekanan diastolik harus antara 40-150 mmHg.');
            }],
            'heart_rate'        => 'nullable|integer|min:30|max:250',
            'respiratory_rate'  => 'nullable|integer|min:5|max:60',
            'body_temperature'  => 'required|numeric|between:35,42',
            'oxygen_saturation' => 'nullable|numeric|min:50|max:100',
            'symptoms'          => 'required|string',
            'notes'             => 'nullable|string',
            'recommendation'    => 'nullable|string',
            'next_visit_date'   => 'nullable|date',
            'status'            => 'required|in:Stable,Need Control,Need Referral',
        ]);

        $monitoring = Monitoring::create($validated);

        $monitoring->load(['patient', 'user' => fn($q) => $q->select('id', 'name', 'email')]);

        return response()->json([
            'success' => true,
            'message' => 'Monitoring berhasil disimpan.',
            'data'    => $monitoring,
        ], 201);
    }

    public function byStatus(string $status)
    {
        $allowed = ['Stable', 'Need Control', 'Need Referral'];
        if (!in_array($status, $allowed)) {
            return response()->json([
                'success' => false,
                'message' => 'Status tidak valid. Pilihan: ' . implode(', ', $allowed),
            ], 422);
        }

        $monitorings = Monitoring::with([
            'patient',
            'user' => fn($q) => $q->select('id', 'name', 'email'),
        ])->where('status', $status)
          ->latest('monitoring_date')
          ->latest('monitoring_time')
          ->get();

        return response()->json([
            'success' => true,
            'message' => "Data monitoring dengan status '$status' berhasil diambil.",
            'data'    => $monitorings,
        ], 200);
    }

    public function show($id)
    {
        $monitoring = Monitoring::with([
            'patient',
            'user' => fn($q) => $q->select('id', 'name', 'email'),
        ])->findOrFail($id);

        return response()->json([
            'success' => true,
            'message' => 'Data monitoring berhasil diambil.',
            'data'    => $monitoring,
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $monitoring = Monitoring::findOrFail($id);

        $validated = $request->validate([
            'patient_id'        => 'sometimes|exists:patients,patient_id',
            'monitoring_date'   => 'sometimes|date',
            'monitoring_time'   => 'nullable|string',
            'blood_pressure'    => ['sometimes', 'regex:/^\d{2,3}\/\d{2,3}$/', function ($attr, $value, $fail) {
                $parts = explode('/', $value);
                $systolic  = (int) $parts[0];
                $diastolic = (int) $parts[1];
                if ($systolic < 60 || $systolic > 250) $fail('Tekanan sistolik harus antara 60-250 mmHg.');
                if ($diastolic < 40 || $diastolic > 150) $fail('Tekanan diastolik harus antara 40-150 mmHg.');
            }],
            'heart_rate'        => 'nullable|integer|min:30|max:250',
            'respiratory_rate'  => 'nullable|integer|min:5|max:60',
            'body_temperature'  => 'sometimes|numeric|between:35,42',
            'oxygen_saturation' => 'nullable|numeric|min:50|max:100',
            'symptoms'          => 'sometimes|string',
            'notes'             => 'nullable|string',
            'recommendation'    => 'nullable|string',
            'next_visit_date'   => 'nullable|date',
            'status'            => 'sometimes|in:Stable,Need Control,Need Referral',
        ]);

        $monitoring->update($validated);
        $monitoring->load(['patient', 'user' => fn($q) => $q->select('id', 'name', 'email')]);

        return response()->json([
            'success' => true,
            'message' => 'Monitoring berhasil diperbarui.',
            'data'    => $monitoring,
        ], 200);
    }

    public function destroy($id)
    {
        $monitoring = Monitoring::findOrFail($id);
        $monitoring->delete();

        return response()->json([
            'success' => true,
            'message' => 'Monitoring berhasil dihapus.',
        ], 200);
    }
}
