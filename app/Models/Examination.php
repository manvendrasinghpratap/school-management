<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Examination extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'examinations';

    protected $fillable = [
        'school_id',
        'academic_year_id',
        'term_id',
        'name',
        'type',
        'start_date',
        'end_date',
        'status',
    ];

    protected $casts = [
        'school_id' => 'integer',
        'academic_year_id' => 'integer',
        'term_id' => 'integer',
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function academicYear(): BelongsTo
    {
        return $this->belongsTo(
            AcademicYears::class,
            'academic_year_id'
        );
    }

    public function term(): BelongsTo
    {
        return $this->belongsTo(
            Terms::class,
            'term_id'
        );
    }

    public function examSchedules(): HasMany
    {
        return $this->hasMany(
            ExamSchedule::class,
            'examination_id'
        );
    }


    public function marks(): HasMany
    {
    return $this->hasMany(
        Mark::class,
        'examination_id'
    );
    }

    public function results(): HasMany
    {
    return $this->hasMany(
        Result::class,
        'examination_id'
    );
    }
}