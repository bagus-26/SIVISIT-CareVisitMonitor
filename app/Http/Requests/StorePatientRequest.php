<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePatientRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $patientId = $this->route('patient_id');

        return [
            'patient_id'          => $patientId
                ? 'sometimes|string|unique:patients,patient_id,' . $patientId . ',patient_id'
                : 'required|string|unique:patients,patient_id',
            'patient_name'        => 'required|string|max:255',
            'nik_dummy'           => 'required|string|max:20',
            'datebirth'           => 'required|date',
            'gender'              => 'required|in:Male,Female',
            'address'             => 'required|string',
            'family_phone'        => 'required|string|max:20',
            'patient_category'    => 'required|string|max:100',
            'assigned_officer_id' => 'nullable|exists:users,id',
        ];
    }

    public function messages(): array
    {
        return [
            'patient_id.required'       => 'Kode pasien wajib diisi.',
            'patient_id.unique'         => 'Kode pasien sudah digunakan.',
            'patient_name.required'     => 'Nama pasien wajib diisi.',
            'nik_dummy.required'        => 'NIK wajib diisi.',
            'datebirth.required'        => 'Tanggal lahir wajib diisi.',
            'datebirth.date'            => 'Format tanggal lahir tidak valid.',
            'gender.required'           => 'Jenis kelamin wajib dipilih.',
            'gender.in'                 => 'Jenis kelamin harus Male atau Female.',
            'address.required'          => 'Alamat wajib diisi.',
            'family_phone.required'     => 'Nomor telepon wajib diisi.',
            'patient_category.required' => 'Kategori pasien wajib dipilih.',
        ];
    }
}
