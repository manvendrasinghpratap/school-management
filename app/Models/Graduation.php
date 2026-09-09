<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Graduation extends Model
{
    use HasFactory;

    protected $table = 'graduations';

    protected $fillable = [
        'school_id',
        'student_id',
        'academic_year_id',
        'graduation_date',
        'qualification',
        'status',
        'approved_by',
        'remarks',
    ];

    protected function casts(): array
    {
        return [
            'graduation_date' => 'date',
        ];
    }

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function academicYear(): BelongsTo
    {
        return $this->belongsTo(
            AcademicYears::class,
            'academic_year_id'
        );
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'approved_by'
        );
    }
}