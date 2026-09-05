<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'class_id',
        'course_id',
        'recorded_by',
        'section_id',
        'student_id',
    ];

    public function student() { return $this->belongsTo(Student::class, 'student_id'); }
    public function course() { return $this->belongsTo(Course::class, 'course_id'); }
    public function classModel() { return $this->belongsTo(ClassModel::class, 'class_id'); }
    public function section() { return $this->belongsTo(Section::class, 'section_id'); }
    public function recordedBy() { return $this->belongsTo(User::class, 'recorded_by'); }
}
