<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Marks extends Model
{
    use HasFactory;

    protected $fillable = [
        'approved_by',
        'course_id',
        'entered_by',
        'examination_id',
        'grade_id',
        'student_id',
    ];

    public function student() { return $this->belongsTo(Student::class, 'student_id'); }
    public function examination() { return $this->belongsTo(Examination::class, 'examination_id'); }
    public function course() { return $this->belongsTo(Course::class, 'course_id'); }
    public function grade() { return $this->belongsTo(Grade::class, 'grade_id'); }
    public function enteredBy() { return $this->belongsTo(User::class, 'entered_by'); }
    public function approvedBy() { return $this->belongsTo(User::class, 'approved_by'); }
}
