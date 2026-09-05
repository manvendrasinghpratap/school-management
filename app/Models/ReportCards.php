<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReportCards extends Model
{
    use HasFactory;

    protected $fillable = [
        'academic_year_id',
        'generated_by',
        'student_id',
        'term_id',
    ];

    public function academic_year() { return $this->belongsTo(AcademicYears::class, 'academic_year_id'); }
    public function student() { return $this->belongsTo(Students::class, 'student_id'); }
    public function term() { return $this->belongsTo(Terms::class, 'term_id'); }
}
