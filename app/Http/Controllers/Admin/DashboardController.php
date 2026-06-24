<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Models\Monitoring;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $todayDate = date('Y-m-d');

        $totalPatients = Patient::query()->count();
        $todayVisits   = Monitoring::query()->where('monitoring_date', $todayDate)->count();
        $todayFinished = Monitoring::query()
            ->where('monitoring_date', $todayDate)
            ->where('status', 'Stable')
            ->count();

        $needControl  = Monitoring::query()->where('status', 'Need Control')->count();
        $needReferral = Monitoring::query()->where('status', 'Need Referral')->count();

        $todayAgenda = Monitoring::query()
            ->with('patient')
            ->where('monitoring_date', $todayDate)
            ->orderBy('monitoring_time')
            ->get();

        $latestIds = Monitoring::query()
            ->select(DB::raw('MAX(id) as id'))
            ->groupBy('patient_id')
            ->pluck('id');

        $monitorPatients = Monitoring::query()
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
            $count = Monitoring::query()->where('monitoring_date', $date)->count();
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
