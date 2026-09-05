<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Graduations extends Model
{
    use HasFactory;

    protected $fillable = [
        'academic_year_id',
        'approved_by',
        'student_id',
    ];

    public function academic_year() { return $this->belongsTo(AcademicYears::class, 'academic_year_id'); }
    public function student() { return $this->belongsTo(Students::class, 'student_id'); }
}
