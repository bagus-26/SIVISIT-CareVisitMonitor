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
        $user = Auth::user();
        $patients = Patient::with('monitorings', 'assignedOfficer');
        if ($user->role === 'petugas') {
            $patients = $patients->where('assigned_officer_id', $user->id);
        }
        return view('patient.pasien', ['patients' => $patients->get()]);
    }

    public function create()
    {
        $petugas = User::where('role', 'petugas')->orderBy('name')->get();
        $lastPatient = Patient::orderBy('id', 'desc')->first();
        if ($lastPatient) {
            $lastNum = (int) substr($lastPatient->patient_id, 1);
            $nextId = 'P' . str_pad($lastNum + 1, 3, '0', STR_PAD_LEFT);
        } else {
            $nextId = 'P001';
        }
        return view('patient.tambah-pasien', compact('petugas', 'nextId'));
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
