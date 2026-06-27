<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Patient;
use App\Models\Monitoring;
use App\Models\LocationLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LocationMonitorController extends Controller
{
    public function adminMap()
    {
        $petugas = User::where('role', 'petugas')
            ->select('id', 'name', 'latitude', 'longitude', 'last_location_at', 'phone', 'location')
            ->get()
            ->map(function ($u) {
                $u->is_online = $u->last_location_at ? $u->last_location_at->gt(now()->subMinutes(15)) : false;
                $u->assigned_patients = Patient::where('assigned_officer_id', $u->id)->count();
                $u->today_visits = Monitoring::where('user_id', $u->id)
                    ->whereDate('monitoring_date', now())
                    ->count();
                return $u;
            });

        $patients = Patient::with('assignedOfficer')
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->get();

        return view('admin.location-monitor', compact('petugas', 'patients'));
    }

    public function petugasTracker()
    {
        $user = Auth::user();
        $assignedPatients = Patient::where('assigned_officer_id', $user->id)->get();
        $todayVisits = Monitoring::where('user_id', $user->id)
            ->whereDate('monitoring_date', now())
            ->count();

        return view('admin.location.petugas', compact('user', 'assignedPatients', 'todayVisits'));
    }

    public function getPetugasPatients($id)
    {
        $petugas = User::findOrFail($id);
        $patients = Patient::where('assigned_officer_id', $id)->get(['patient_id', 'patient_name']);
        $otherPetugas = User::where('role', 'petugas')
            ->where('id', '!=', $id)
            ->select('id', 'name')
            ->get();

        return response()->json([
            'patients' => $patients,
            'other_petugas' => $otherPetugas,
        ]);
    }

    public function updateLocation(Request $request)
    {
        $validated = $request->validate([
            'latitude'  => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'accuracy'  => 'nullable|numeric',
        ]);

        $user = Auth::user();

        $user->latitude = $validated['latitude'];
        $user->longitude = $validated['longitude'];
        $user->last_location_at = now();
        $user->save();

        LocationLog::create([
            'user_id'     => $user->id,
            'latitude'    => $validated['latitude'],
            'longitude'   => $validated['longitude'],
            'accuracy'    => $validated['accuracy'] ?? null,
            'source'      => 'gps',
            'recorded_at' => now(),
        ]);

        return response()->json(['success' => true]);
    }
}
