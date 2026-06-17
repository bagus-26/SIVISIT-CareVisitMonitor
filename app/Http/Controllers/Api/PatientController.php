<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Patient;

class PatientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $patients = Patient::with('monitorings')->get();

        return response()->json([
            'success' => true,
            'message' => 'Patients list have been retrieved successfully.',
            'data' => $patients
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'patient_id' => 'required|string|unique:patients,patient_id',
            'patient_name' => 'required|string|max:255',
            'nik_dummy' => 'required|string|max:20',
            'datebirth' => 'required|date',
            'gender' => 'required|in:Male,Female',
            'address' => 'required|string',
            'family_phone' => 'required|string',
            'patient_category' => 'required|string',
            'monitoring_date' => 'nullable|string', // can contain date-time or date
        ]);

        $patient = Patient::create([
            'patient_id' => $validatedData['patient_id'],
            'patient_name' => $validatedData['patient_name'],
            'nik_dummy' => $validatedData['nik_dummy'],
            'datebirth' => $validatedData['datebirth'],
            'gender' => $validatedData['gender'],
            'address' => $validatedData['address'],
            'family_phone' => $validatedData['family_phone'],
            'patient_category' => $validatedData['patient_category'],
        ]);

        // If monitoring_date is provided, register initial monitoring
        if (!empty($validatedData['monitoring_date'])) {
            $dateTime = strtotime($validatedData['monitoring_date']);
            $date = date('Y-m-d', $dateTime);
            $time = date('H:i:s', $dateTime);

            // Get user_id from query or fallback
            $userId = $request->input('user_id') ?? 1;

            \App\Models\Monitoring::create([
                'patient_id' => $patient->patient_id,
                'user_id' => $userId,
                'monitoring_date' => $date,
                'monitoring_time' => $time,
                'status' => 'Stable',
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Patient created successfully.',
            'data' => $patient
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
     $patient =Patient::with('monitorings')->where('patient_id', $id)->first();

     if (!$patient) {
         return response()->json([
             'success' => false,
             'message' => 'Patient not found.'
         ], 404);
     }

     return response()->json([
         'success' => true,
         'message' => 'Patient details have been retrieved successfully.',
         'data' => $patient
     ], 200);
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
