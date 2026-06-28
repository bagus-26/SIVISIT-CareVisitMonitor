<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Monitoring;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MonitoringController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $isPetugas = $user->role === 'petugas';

        $query = Monitoring::with('patient.assignedOfficer', 'user');

        if ($isPetugas) {
            $patientIds = Patient::where('assigned_officer_id', $user->id)->pluck('patient_id');
            $query->whereIn('patient_id', $patientIds);
        }

        $monitorings = $query->orderBy('monitoring_date', 'desc')
            ->orderBy('monitoring_time', 'desc')
            ->get();

        $baseQuery = $isPetugas
            ? Monitoring::whereIn('patient_id', Patient::where('assigned_officer_id', $user->id)->pluck('patient_id'))
            : Monitoring::query();

        $countStable = (clone $baseQuery)->where('status', 'Stable')->count();
        $countControl = (clone $baseQuery)->where('status', 'Need Control')->count();
        $countReferral = (clone $baseQuery)->where('status', 'Need Referral')->count();

        return view('monitoring.index', compact(
            'monitorings',
            'countStable',
            'countControl',
            'countReferral'
        ));
    }

    public function create(Request $request)
    {
        $patients = Patient::with('assignedOfficer')->orderBy('patient_name', 'asc')->get();
        $prePatientId = $request->query('patient_id', '');

        return view('monitoring.create', compact('patients', 'prePatientId'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,patient_id',
            'monitoring_date' => 'required|date',
            'monitoring_time' => 'nullable',
            'blood_pressure' => [
                'required',
                'regex:/^\d{2,3}\/\d{2,3}$/',
                function ($attribute, $value, $fail) {
                    [$sys, $dia] = explode('/', $value);
                    if ($sys < 60 || $sys > 250 || $dia < 40 || $dia > 150) {
                        $fail('Format tekanan darah tidak valid. Nilai sistolik: 60–250, diastolik: 40–150.');
                    }
                }
            ],
            'body_temperature' => 'required|numeric|between:35.0,42.0',
            'heart_rate' => 'nullable|integer|between:30,250',
            'respiratory_rate' => 'nullable|integer|between:5,60',
            'oxygen_saturation' => 'nullable|numeric|between:50,100',
            'symptoms' => 'required|string',
            'notes' => 'nullable|string',
            'recommendation' => 'nullable|string',
            'next_visit_date' => 'nullable|date',
            'status' => 'required|in:Stable,Need Control,Need Referral',
        ]);

        $patient = Patient::find($validated['patient_id']);
        $validated['user_id'] = $patient && $patient->assigned_officer_id
            ? $patient->assigned_officer_id
            : (Auth::id() ?? 1);

        Monitoring::create($validated);

        return redirect()->route('admin.monitorings.index')->with('success', 'Catatan monitoring berhasil disimpan.');
    }

    public function show($id)
    {
        $monitoring = Monitoring::with('patient', 'user')->findOrFail($id);

        $patientHistory = Monitoring::where('patient_id', $monitoring->patient_id)
            ->orderBy('monitoring_date', 'desc')
            ->orderBy('monitoring_time', 'desc')
            ->get();

        return view('monitoring.show', compact('monitoring', 'patientHistory'));
    }

    public function edit($id)
    {
        $monitoring = Monitoring::with('patient')->findOrFail($id);
        $patients   = Patient::orderBy('patient_name', 'asc')->get();

        return view('monitoring.edit', compact('monitoring', 'patients'));
    }

    public function update(Request $request, $id)
    {
        $monitoring = Monitoring::findOrFail($id);

        $validated = $request->validate([
            'patient_id'        => 'required|exists:patients,patient_id',
            'monitoring_date'   => 'required|date',
            'monitoring_time'   => 'nullable',
            'blood_pressure'    => [
                'required',
                'regex:/^\d{2,3}\/\d{2,3}$/',
                function ($attribute, $value, $fail) {
                    [$sys, $dia] = explode('/', $value);
                    if ($sys < 60 || $sys > 250 || $dia < 40 || $dia > 150) {
                        $fail('Format tekanan darah tidak valid. Nilai sistolik: 60–250, diastolik: 40–150.');
                    }
                }
            ],
            'body_temperature'  => 'required|numeric|between:35.0,42.0',
            'heart_rate'        => 'nullable|integer|between:30,250',
            'respiratory_rate'  => 'nullable|integer|between:5,60',
            'oxygen_saturation' => 'nullable|numeric|between:50,100',
            'symptoms'          => 'required|string',
            'notes'             => 'nullable|string',
            'recommendation'    => 'nullable|string',
            'next_visit_date'   => 'nullable|date',
            'status'            => 'required|in:Stable,Need Control,Need Referral',
        ]);

        $monitoring->update($validated);

        return redirect()
            ->route('admin.monitorings.show', $monitoring->id)
            ->with('success', 'Catatan monitoring berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $monitoring = Monitoring::findOrFail($id);
        $monitoring->delete();

        return redirect()
            ->route('admin.monitorings.index')
            ->with('success', 'Catatan monitoring berhasil dihapus.');
    }
}
