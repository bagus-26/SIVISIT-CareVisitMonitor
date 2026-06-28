<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Models\Monitoring;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $todayDate = date('Y-m-d');
        $user = Auth::user();
        $isPetugas = $user->role === 'petugas';

        if ($isPetugas) {
            $patientIds = Patient::where('assigned_officer_id', $user->id)->pluck('patient_id');
            $baseQuery = Monitoring::whereIn('patient_id', $patientIds);
            $totalPatients = Patient::where('assigned_officer_id', $user->id)->count();
        } else {
            $baseQuery = Monitoring::query();
            $totalPatients = Patient::query()->count();
        }

        $todayVisits   = (clone $baseQuery)->where('monitoring_date', $todayDate)->count();
        $todayFinished = (clone $baseQuery)
            ->where('monitoring_date', $todayDate)
            ->where('status', 'Stable')
            ->count();

        $needControl  = (clone $baseQuery)->where('status', 'Need Control')->count();
        $needReferral = (clone $baseQuery)->where('status', 'Need Referral')->count();

        $todayAgenda = (clone $baseQuery)
            ->with('patient.assignedOfficer')
            ->where('monitoring_date', $todayDate)
            ->orderBy('monitoring_time')
            ->get();

        $latestIds = (clone $baseQuery)
            ->select(DB::raw('MAX(id) as id'))
            ->groupBy('patient_id')
            ->pluck('id');

        $monitorPatients = (clone $baseQuery)
            ->with('patient')
            ->whereIn('id', $latestIds)
            ->orderByDesc('monitoring_date')
            ->limit(8)
            ->get()
            ->map(function ($m) {
                $p = $m->patient;
                return (object) [
                    'name'   => $p->patient_name ?? 'Unknown',
                    'status' => $m->status,
                    'color'  => $m->status === 'Stable' ? '#34C759' : ($m->status === 'Need Referral' ? '#FF3B30' : '#FF9500'),
                ];
            });

        $weeklyVisits = [];
        $dayLabels    = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = date('Y-m-d', strtotime("-$i days"));
            $count = (clone $baseQuery)->where('monitoring_date', $date)->count();
            $weeklyVisits[] = $count;
            $dayLabels[]    = strtoupper(\Carbon\Carbon::parse($date)->isoFormat('dd'));
        }
        $maxWeekly = max($weeklyVisits) ?: 1;

        return view('admin.dashboard', compact(
            'totalPatients', 'todayVisits', 'todayFinished',
            'todayAgenda', 'needControl', 'needReferral',
            'monitorPatients', 'weeklyVisits', 'dayLabels', 'maxWeekly'
        ));
    }
}
