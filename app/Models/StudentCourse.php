<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentCourse extends Model
{
    use HasFactory;

    protected $fillable = [
        'academic_year_id',
        'course_id',
        'student_id',
        'term_id',
    ];

    public function student() { return $this->belongsTo(Student::class, 'student_id'); }
    public function course() { return $this->belongsTo(Course::class, 'course_id'); }
    public function academicYear() { return $this->belongsTo(AcademicYear::class, 'academic_year_id'); }
    public function term() { return $this->belongsTo(Term::class, 'term_id'); }
}
