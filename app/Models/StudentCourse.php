<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentCourse extends Model
{
    use HasFactory;

    protected $table = 'student_courses';

    protected $fillable = [
        'student_id',
        'course_id',
        'academic_year_id',
        'term_id',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'student_id' => 'integer',
            'course_id' => 'integer',
            'academic_year_id' => 'integer',
            'term_id' => 'integer',
        ];
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Courses::class, 'course_id');
    }

    public function academicYear(): BelongsTo
    {
        return $this->belongsTo(AcademicYears::class, 'academic_year_id');
    }

    public function term(): BelongsTo
    {
        return $this->belongsTo(Terms::class, 'term_id');
    }
}