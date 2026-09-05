<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'class_id',
        'course_id',
        'examination_id',
        'invigilator_id',
        'section_id',
    ];

    public function examination() { return $this->belongsTo(Examination::class, 'examination_id'); }
    public function course() { return $this->belongsTo(Course::class, 'course_id'); }
    public function classModel() { return $this->belongsTo(ClassModel::class, 'class_id'); }
    public function section() { return $this->belongsTo(Section::class, 'section_id'); }
    public function invigilator() { return $this->belongsTo(Staff::class, 'invigilator_id'); }
}
