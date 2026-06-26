<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\LocationLog;
use App\Models\User;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LocationController extends Controller
{
    public function update(Request $request)
    {
        $validated = $request->validate([
            'latitude'  => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'accuracy'  => 'nullable|numeric',
            'altitude'  => 'nullable|numeric',
            'speed'     => 'nullable|numeric',
            'heading'   => 'nullable|numeric',
            'source'    => 'nullable|string|in:gps,network,manual',
        ]);

        $user = $request->user();

        // Update user's current location
        $user->latitude = $validated['latitude'];
        $user->longitude = $validated['longitude'];
        $user->last_location_at = now();
        $user->save();

        // Log location history
        $log = LocationLog::create([
            'user_id'     => $user->id,
            'latitude'    => $validated['latitude'],
            'longitude'   => $validated['longitude'],
            'accuracy'    => $validated['accuracy'] ?? null,
            'altitude'    => $validated['altitude'] ?? null,
            'speed'       => $validated['speed'] ?? null,
            'heading'     => $validated['heading'] ?? null,
            'source'      => $validated['source'] ?? 'gps',
            'recorded_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Lokasi berhasil diperbarui.',
            'data'    => $log,
        ], 200);
    }

    public function petugas(Request $request)
    {
        $users = User::whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->whereNotNull('last_location_at')
            ->select('id', 'name', 'role', 'latitude', 'longitude', 'last_location_at', 'location')
            ->get()
            ->map(function ($u) {
                $u->last_location_at_diff = $u->last_location_at ? $u->last_location_at->diffForHumans() : null;
                return $u;
            });

        return response()->json([
            'success' => true,
            'message' => 'Lokasi petugas berhasil diambil.',
            'data'    => $users,
        ], 200);
    }

    public function history(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'nullable|exists:users,id',
            'limit'   => 'nullable|integer|min:1|max:100',
            'minutes' => 'nullable|integer|min:1|max:1440',
        ]);

        $query = LocationLog::query()->with('user:id,name');
        $userId = $validated['user_id'] ?? $request->user()->id;
        $query->where('user_id', $userId);

        if (!empty($validated['minutes'])) {
            $query->where('recorded_at', '>=', now()->subMinutes($validated['minutes']));
        }

        $limit = $validated['limit'] ?? 50;
        $logs = $query->latest('recorded_at')->limit($limit)->get();

        return response()->json([
            'success' => true,
            'message' => 'Riwayat lokasi berhasil diambil.',
            'data'    => $logs,
        ], 200);
    }

    public function nearby(Request $request)
    {
        $validated = $request->validate([
            'latitude'  => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'radius'    => 'nullable|numeric|min:0.1|max:100',
        ]);

        $radius = $validated['radius'] ?? 5;

        $patients = Patient::selectRaw("
            *,
            (6371 * acos(cos(radians(?)) * cos(radians(latitude))
            * cos(radians(longitude) - radians(?))
            + sin(radians(?)) * sin(radians(latitude)))) AS distance
        ", [
            $validated['latitude'],
            $validated['longitude'],
            $validated['latitude'],
        ])
        ->whereNotNull('latitude')
        ->whereNotNull('longitude')
        ->having('distance', '<=', $radius)
        ->orderBy('distance')
        ->get();

        return response()->json([
            'success' => true,
            'message' => "Pasien dalam radius {$radius} km berhasil diambil.",
            'data'    => $patients,
        ], 200);
    }

    public function geocode(Request $request)
    {
        $validated = $request->validate([
            'address' => 'required|string|max:500',
        ]);

        $address = urlencode($validated['address'] . ', Indonesia');
        $url = "https://nominatim.openstreetmap.org/search?format=json&q={$address}&limit=1";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_USERAGENT, 'SIVISIT-CareVisitMonitor/1.0');
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        $result = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode !== 200 || empty($result)) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal melakukan geocoding.',
                'data'    => null,
            ], 422);
        }

        $data = json_decode($result, true);
        if (empty($data)) {
            return response()->json([
                'success' => false,
                'message' => 'Alamat tidak ditemukan.',
                'data'    => null,
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Geocoding berhasil.',
            'data'    => [
                'latitude'  => $data[0]['lat'],
                'longitude' => $data[0]['lon'],
                'display_name' => $data[0]['display_name'],
            ],
        ], 200);
    }
}
