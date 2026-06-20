<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Models\Monitoring;
use Illuminate\Http\Request;

class RekamMedisController extends Controller
{
    public function index(Request $request)
    {
        $patients = Patient::orderBy('patient_name', 'asc')->get();
        
        $filterPatientId = $request->query('patient_id');
        if ($filterPatientId) {
            $displayPatients = Patient::where('patient_id', $filterPatientId)->get();
        } else {
            $displayPatients = $patients;
        }

        $totalVisits = Monitoring::count();
        $countStable = Monitoring::where('status', 'Stable')->count();
        $countControl = Monitoring::where('status', 'Need Control')->count();
        $countReferral = Monitoring::where('status', 'Need Referral')->count();

        $monByPatient = Monitoring::orderBy('monitoring_date', 'desc')
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
