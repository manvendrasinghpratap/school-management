<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Timetable extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'timetables';

    protected $fillable = [
        'school_id',
        'course_id',
        'class_id',
        'section_id',
        'staff_id',
        'academic_year_id',
        'term_id',
        'day_of_week',
        'start_time',
        'end_time',
        'room',
    ];

    protected function casts(): array
    {
        return [
            'start_time' => 'datetime:H:i',
            'end_time' => 'datetime:H:i',
        ];
    }

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Courses::class, 'course_id');
    }

    public function class(): BelongsTo
    {
        return $this->belongsTo(Classes::class, 'class_id');
    }

    public function section(): BelongsTo
    {
        return $this->belongsTo(Section::class, 'section_id');
    }

    public function staff(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'staff_id');
    }

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
}