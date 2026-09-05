<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicalRecords extends Model
{
    use HasFactory;

    protected $fillable = [
        'recorded_by',
        'staff_id',
        'student_id',
    ];

    public function staff() { return $this->belongsTo(Staff::class, 'staff_id'); }
    public function student() { return $this->belongsTo(Students::class, 'student_id'); }
}
