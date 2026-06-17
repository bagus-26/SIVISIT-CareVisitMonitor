<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\monitoring;
use Illuminate\Http\Request;

class MonitoringController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $monitorings = \App\Models\Monitoring::with('patient')->get();

        return response()->json([
            'success' => true,
            'message' => 'Monitoring list retrieved successfully.',
            'data' => $monitorings
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'patient_id' => 'required|exists:patients,patient_id',
            'user_id' => 'required|exists:users,id',
            'monitoring_date' => 'required|date',
            'blood_pressure' => 'nullable|string',
            'heart_rate' => 'nullable|integer',
            'respiratory_rate' => 'nullable|integer',
            'body_temperature' => 'nullable|numeric',
            'oxygen_saturation' => 'nullable|integer',
            'symptoms' => 'nullable|string',
            'notes' => 'nullable|string',
            'status' => 'required|in:Stable,Unstable',

        ]);

        $monitoring = Monitoring::create($validatedData);

        return response()->json([
            'success' => true,
            'message' => 'Monitoring record created successfully.',
            'data' => $monitoring
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
