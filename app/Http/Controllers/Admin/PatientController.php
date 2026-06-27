<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePatientRequest;
use App\Models\Patient;
use App\Models\Monitoring;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PatientController extends Controller
{
    public function index()
    {
        $patients = Patient::with('monitorings', 'assignedOfficer')->get();
        return view('patient.pasien', compact('patients'));
    }

    public function create()
    {
        $petugas = User::where('role', 'petugas')->orderBy('name')->get();
        return view('patient.tambah-pasien', compact('petugas'));
    }

    public function store(StorePatientRequest $request)
    {
        $data = $request->validated();
        $officerId = $data['assigned_officer_id'] ?? Auth::id();
        $data['assigned_officer_id'] = $officerId;

        $patient = Patient::create($data);

        if ($request->filled('monitoring_date')) {
            $dateTime = strtotime($request->input('monitoring_date'));
            $date = date('Y-m-d', $dateTime);
            $time = date('H:i:s', $dateTime);

            Monitoring::create([
                'patient_id' => $patient->patient_id,
                'user_id' => $officerId,
                'monitoring_date' => $date,
                'monitoring_time' => $time,
                'status' => 'Stable',
            ]);
        }

        return redirect()->route('admin.patients.index')->with('success', 'Pasien baru berhasil didaftarkan.');
    }

    public function edit($patient_id)
    {
        $patient = Patient::findOrFail($patient_id);
        $petugas = User::where('role', 'petugas')->orderBy('name')->get();
        return view('patient.edit-pasien', compact('patient', 'petugas'));
    }

    public function update(StorePatientRequest $request, $patient_id)
    {
        $patient = Patient::findOrFail($patient_id);
        $data = $request->validated();

        if (Auth::user()->role !== 'admin') {
            unset($data['assigned_officer_id']);
        }

        $patient->update($data);

        return redirect()->route('admin.patients.index')->with('success', 'Data pasien berhasil diperbarui.');
    }

    public function destroy($patient_id)
    {
        $patient = Patient::findOrFail($patient_id);
        
        $patient->monitorings()->delete();
        $patient->delete();

        return redirect()->route('admin.patients.index')->with('success', 'Data pasien berhasil dihapus.');
    }

    public function reassign(Request $request, $patient_id)
    {
        $request->validate([
            'assigned_officer_id' => 'required|exists:users,id',
        ]);

        $patient = Patient::findOrFail($patient_id);
        $patient->assigned_officer_id = $request->assigned_officer_id;
        $patient->save();

        return response()->json(['success' => true, 'message' => 'Pasien berhasil dialihkan.']);
    }
}
