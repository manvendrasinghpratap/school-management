<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'uploaded_by',
    ];

    public function student() { return $this->belongsTo(Student::class, 'student_id'); }
    public function uploadedBy() { return $this->belongsTo(User::class, 'uploaded_by'); }
}
