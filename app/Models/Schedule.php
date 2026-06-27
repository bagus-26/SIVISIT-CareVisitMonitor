<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'user_id',
        'tanggal',
        'jam',
        'durasi',
        'tujuan',
        'status',
        'catatan',
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
