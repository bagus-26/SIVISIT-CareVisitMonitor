<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
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

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'patient_id' => 'required|string|unique:patients,patient_id',
            'patient_name' => 'required|string|max:255',
            'nik_dummy' => 'required|string|max:20',
            'datebirth' => 'required|date',
            'gender' => 'required|in:Male,Female',
            'address' => 'required|string',
            'family_phone' => 'required|string',
            'patient_category' => 'required|string',
            'monitoring_date' => 'nullable|string',
        ]);

        $patient = Patient::create([
            'patient_id' => $validatedData['patient_id'],
            'patient_name' => $validatedData['patient_name'],
            'nik_dummy' => $validatedData['nik_dummy'],
            'datebirth' => $validatedData['datebirth'],
            'gender' => $validatedData['gender'],
            'address' => $validatedData['address'],
            'family_phone' => $validatedData['family_phone'],
            'patient_category' => $validatedData['patient_category'],
        ]);

        if (!empty($validatedData['monitoring_date'])) {
            $dateTime = strtotime($validatedData['monitoring_date']);
            $date = date('Y-m-d', $dateTime);
            $time = date('H:i:s', $dateTime);

            Monitoring::create([
                'patient_id' => $patient->patient_id,
                'user_id' => Auth::id() ?? 1,
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

    public function update(Request $request, $patient_id)
    {
        $patient = Patient::findOrFail($patient_id);

        $validatedData = $request->validate([
            'patient_name' => 'required|string|max:255',
            'nik_dummy' => 'required|string|max:20',
            'datebirth' => 'required|date',
            'gender' => 'required|in:Male,Female',
            'address' => 'required|string',
            'family_phone' => 'required|string',
            'patient_category' => 'required|string',
        ]);

        $patient->update($validatedData);

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
