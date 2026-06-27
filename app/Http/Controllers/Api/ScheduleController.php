<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    public function index(Request $request)
    {
        $query = Schedule::with(['patient', 'user' => fn($q) => $q->select('id', 'name', 'email')]);

        if ($patientId = $request->query('patient_id')) {
            $query->where('patient_id', $patientId);
        }

        if ($status = $request->query('status')) {
            $query->where('status', $status);
        }
        
        if ($date = $request->query('tanggal')) {
            $query->where('tanggal', $date);
        }

        $schedules = $query->orderBy('tanggal', 'asc')->orderBy('jam', 'asc')->get();

        return response()->json([
            'success' => true,
            'message' => 'Data jadwal berhasil diambil.',
            'data'    => $schedules,
        ], 200);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,patient_id',
            'user_id'    => 'nullable|exists:users,id',
            'tanggal'    => 'required|date',
            'jam'        => 'required|date_format:H:i',
            'durasi'     => 'required|integer|min:15',
            'tujuan'     => 'required|string|max:255',
            'status'     => 'nullable|in:scheduled,done,cancelled',
            'catatan'    => 'nullable|string',
        ]);

        $schedule = Schedule::create($validated);
        
        $schedule->load(['patient']);

        return response()->json([
            'success' => true,
            'message' => 'Jadwal berhasil ditambahkan.',
            'data'    => $schedule,
        ], 201);
    }

    public function show($id)
    {
        $schedule = Schedule::with(['patient', 'user' => fn($q) => $q->select('id', 'name', 'email')])->findOrFail($id);

        return response()->json([
            'success' => true,
            'message' => 'Data jadwal berhasil diambil.',
            'data'    => $schedule,
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $schedule = Schedule::findOrFail($id);

        $validated = $request->validate([
            'patient_id' => 'sometimes|exists:patients,patient_id',
            'user_id'    => 'nullable|exists:users,id',
            'tanggal'    => 'sometimes|date',
            'jam'        => 'sometimes|date_format:H:i:s,H:i',
            'durasi'     => 'sometimes|integer|min:15',
            'tujuan'     => 'sometimes|string|max:255',
            'status'     => 'sometimes|in:scheduled,done,cancelled',
            'catatan'    => 'nullable|string',
        ]);

        $schedule->update($validated);
        
        $schedule->load(['patient']);

        return response()->json([
            'success' => true,
            'message' => 'Jadwal berhasil diperbarui.',
            'data'    => $schedule,
        ], 200);
    }

    public function destroy($id)
    {
        $schedule = Schedule::findOrFail($id);
        $schedule->delete();

        return response()->json([
            'success' => true,
            'message' => 'Jadwal berhasil dihapus.',
        ], 200);
    }
}
