<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Models\Monitoring;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $query = trim($request->query('q', ''));
        $results = collect();
        $found = null;
        $patientMonitorings = collect();

        if ($query !== '') {
            $results = Patient::where('patient_name', 'like', "%{$query}%")
                ->orWhere('nik_dummy', 'like', "%{$query}%")
                ->orWhere('patient_id', 'like', "%{$query}%")
                ->with('monitorings')
                ->get();

            if ($results->count() === 1) {
                $found = $results->first();
                $patientMonitorings = Monitoring::where('patient_id', $found->patient_id)
                    ->orderBy('monitoring_date', 'desc')
                    ->orderBy('monitoring_time', 'desc')
                    ->get();
            }
        }

        return view('admin.search', compact('query', 'results', 'found', 'patientMonitorings'));
    }
}
