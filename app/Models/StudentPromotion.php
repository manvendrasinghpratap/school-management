<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentPromotion extends Model
{
    use HasFactory;

    protected $fillable = [
        'academic_year_id',
        'approved_by',
        'from_class_id',
        'student_id',
        'to_class_id',
    ];

    public function student() { return $this->belongsTo(Student::class, 'student_id'); }
    public function fromClass() { return $this->belongsTo(ClassModel::class, 'from_class_id'); }
    public function toClass() { return $this->belongsTo(ClassModel::class, 'to_class_id'); }
    public function academicYear() { return $this->belongsTo(AcademicYear::class, 'academic_year_id'); }
    public function approvedBy() { return $this->belongsTo(User::class, 'approved_by'); }
}
