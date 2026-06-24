<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Models\Monitoring;
use App\Models\UsageLog;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index()
    {
        $monthYear = request('month', now()->format('Y-m'));
        $startDate = Carbon::createFromFormat('Y-m', $monthYear)->startOfMonth();
        $endDate = $startDate->copy()->endOfMonth();

        $totalPatients = Patient::count();
        $allMonitorings = Monitoring::whereBetween('created_at', [$startDate, $endDate])->get();

        $totalMonitorings = $allMonitorings->count();
        $totalMonitoringsStable = $allMonitorings->filter(fn($m) => stripos($m->status ?? '', 'stabil') !== false)->count();
        $totalMonitoringsNeedControl = $allMonitorings->filter(fn($m) => stripos($m->status ?? '', 'control') !== false)->count();

        // Daily Monitorings grouped
        $dailyMonitorings = $allMonitorings->groupBy(function($item) {
            return Carbon::parse($item->created_at)->format('Y-m-d');
        })->map(function($items, $date) {
            return (object) [
                'date' => $date,
                'count' => $items->count(),
                'stable_count' => $items->filter(fn($m) => stripos($m->status ?? '', 'stabil') !== false)->count(),
                'control_count' => $items->filter(fn($m) => stripos($m->status ?? '', 'control') !== false)->count(),
            ];
        })->sortBy('date')->values();

        // Monitorings by Staff
        $monitoringsByStaff = $allMonitorings->groupBy('user_id')->map(function($items) {
            $user = $items->first()->user;
            return (object) [
                'id' => $user->id ?? null,
                'name' => $user->name ?? 'Unknown',
                'total' => $items->count(),
                'stable' => $items->filter(fn($m) => stripos($m->status ?? '', 'stabil') !== false)->count(),
            ];
        })->values();

        try {
            $patientsByLocation = Patient::get()->groupBy('location')->map(function($items, $location) {
                return (object) ['location' => $location, 'count' => $items->count()];
            })->values();
        } catch (\Exception $e) {
            $patientsByLocation = collect();
        }

        return view('admin.reports.index', compact(
            'monthYear',
            'totalPatients',
            'totalMonitorings',
            'totalMonitoringsStable',
            'totalMonitoringsNeedControl',
            'dailyMonitorings',
            'monitoringsByStaff',
            'patientsByLocation'
        ));
    }

    public function exportPdf()
    {
        // Implementation untuk export PDF
        return redirect()->route('admin.reports.index')->with('info', 'Fitur export PDF sedang dikembangkan');
    }

    public function exportExcel()
    {
        // Implementation untuk export Excel
        return redirect()->route('admin.reports.index')->with('info', 'Fitur export Excel sedang dikembangkan');
    }
}
