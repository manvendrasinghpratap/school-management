<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Timetables extends Model
{
    use HasFactory;

    protected $fillable = [
        'class_id',
        'course_id',
        'section_id',
        'staff_id',
    ];

    public function class() { return $this->belongsTo(Classes::class, 'class_id'); }
    public function course() { return $this->belongsTo(Courses::class, 'course_id'); }
    public function section() { return $this->belongsTo(Sections::class, 'section_id'); }
    public function staff() { return $this->belongsTo(Staff::class, 'staff_id'); }
}
