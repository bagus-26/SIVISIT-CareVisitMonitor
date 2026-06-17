<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Models\Monitoring;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $todayDate = date('Y-m-d');

        $totalPatients = Patient::query()->count();
        $todayVisits = Monitoring::query()->where('monitoring_date', $todayDate)->count();
        $todayFinished = Monitoring::query()
            ->where('monitoring_date', $todayDate)
            ->where('status', 'Stable')
            ->count();

        $todayAgenda = Monitoring::query()
            ->with('patient')
            ->where('monitoring_date', $todayDate)
            ->orderBy('monitoring_time')
            ->get();

        return view('admin.dashboard', compact('totalPatients', 'todayVisits', 'todayFinished', 'todayAgenda'));
    }
}
