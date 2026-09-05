<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseAssignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'academic_year_id',
        'class_id',
        'course_id',
        'section_id',
        'staff_id',
        'term_id',
    ];

    public function staff() { return $this->belongsTo(Staff::class, 'staff_id'); }
    public function course() { return $this->belongsTo(Course::class, 'course_id'); }
    public function classModel() { return $this->belongsTo(ClassModel::class, 'class_id'); }
    public function section() { return $this->belongsTo(Section::class, 'section_id'); }
    public function academicYear() { return $this->belongsTo(AcademicYear::class, 'academic_year_id'); }
    public function term() { return $this->belongsTo(Term::class, 'term_id'); }
}
