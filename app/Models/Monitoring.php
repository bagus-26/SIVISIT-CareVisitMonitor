<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Monitoring extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'user_id',
        'monitoring_date',
        'blood_pressure',
        'heart_rate',
        'respiratory_rate',
        'body_temperature',
        'oxygen_saturation',
        'symptoms',
        'notes',
        'recommendation',
        'next_visit_date',
        'status',
        'monitoring_time',
        'examination_focus',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class, 'patient_id', 'patient_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
