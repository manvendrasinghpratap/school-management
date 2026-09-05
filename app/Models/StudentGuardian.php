<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentGuardian extends Model
{
    use HasFactory;

    protected $fillable = [
        'guardian_id',
        'student_id',
    ];

    public function student() { return $this->belongsTo(Student::class, 'student_id'); }
    public function guardian() { return $this->belongsTo(Guardian::class, 'guardian_id'); }
}
