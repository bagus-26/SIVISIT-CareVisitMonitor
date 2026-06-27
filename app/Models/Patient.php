<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Patient extends Model
{
    use HasFactory;

    protected $primaryKey = 'patient_id';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'patient_id',
        'patient_name',
        'nik_dummy',
        'datebirth',
        'gender',
        'address',
        'latitude',
        'longitude',
        'family_phone',
        'patient_category',
        'assigned_officer_id',
    ];

    public function monitorings()
    {
        return $this->hasMany(Monitoring::class, 'patient_id');
    }

    public function assignedOfficer()
    {
        return $this->belongsTo(User::class, 'assigned_officer_id');
    }
}
