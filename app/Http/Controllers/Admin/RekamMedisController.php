<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Models\Monitoring;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RekamMedisController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $isPetugas = $user->role === 'petugas';

        if ($isPetugas) {
            $patientIds = Patient::where('assigned_officer_id', $user->id)->pluck('patient_id');
            $patients = Patient::whereIn('patient_id', $patientIds)
                ->orderBy('patient_name', 'asc')
                ->get();
        } else {
            $patients = Patient::orderBy('patient_name', 'asc')->get();
        }

        $filterPatientId = $request->query('patient_id');
        if ($filterPatientId) {
            $displayPatients = Patient::where('patient_id', $filterPatientId)->get();
        } else {
            $displayPatients = $patients;
        }

        $baseQuery = $isPetugas
            ? Monitoring::whereIn('patient_id', Patient::where('assigned_officer_id', $user->id)->pluck('patient_id'))
            : Monitoring::query();

        $totalVisits = (clone $baseQuery)->count();
        $countStable = (clone $baseQuery)->where('status', 'Stable')->count();
        $countControl = (clone $baseQuery)->where('status', 'Need Control')->count();
        $countReferral = (clone $baseQuery)->where('status', 'Need Referral')->count();

        $monByPatient = (clone $baseQuery)
            ->orderBy('monitoring_date', 'desc')
            ->orderBy('monitoring_time', 'desc')
            ->get()
            ->groupBy('patient_id');

        return view('rekam-medis.index', compact(
            'patients',
            'displayPatients',
            'totalVisits',
            'countStable',
            'countControl',
            'countReferral',
            'monByPatient'
        ));
    }
}
