<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePatientRequest;
use App\Models\Patient;
use App\Models\Monitoring;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PatientController extends Controller
{
    public function index()
    {
        $patients = Patient::with('monitorings')->get();
        return view('patient.pasien', compact('patients'));
    }

    public function create()
    {
        return view('patient.tambah-pasien');
    }

    public function store(StorePatientRequest $request)
    {
        $patient = Patient::create($request->validated());

        if ($request->filled('monitoring_date')) {
            $dateTime = strtotime($request->input('monitoring_date'));
            $date = date('Y-m-d', $dateTime);
            $time = date('H:i:s', $dateTime);

            Monitoring::create([
                'patient_id' => $patient->patient_id,
                'user_id' => Auth::id(),
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
        return view('patient.edit-pasien', compact('patient'));
    }

    public function update(StorePatientRequest $request, $patient_id)
    {
        $patient = Patient::findOrFail($patient_id);

        $patient->update($request->validated());

        return redirect()->route('admin.patients.index')->with('success', 'Data pasien berhasil diperbarui.');
    }

    public function destroy($patient_id)
    {
        $patient = Patient::findOrFail($patient_id);
        
        // Delete monitorings first (cascade/manual)
        $patient->monitorings()->delete();
        $patient->delete();

        return redirect()->route('admin.patients.index')->with('success', 'Data pasien berhasil dihapus.');
    }
}
