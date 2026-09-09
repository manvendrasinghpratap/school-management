<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attendance extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'attendance';

    protected $fillable = [
        'school_id',
        'student_id',
        'course_id',
        'class_id',
        'section_id',
        'attendance_date',
        'status',
        'recorded_by',
        'remarks',
    ];

    protected $casts = [
        'school_id' => 'integer',
        'student_id' => 'integer',
        'course_id' => 'integer',
        'class_id' => 'integer',
        'section_id' => 'integer',
        'attendance_date' => 'date',
        'recorded_by' => 'integer',
    ];

    /**
     * Student
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(
            Student::class,
            'student_id'
        );
    }

    /**
     * Course / Subject
     */
    public function course(): BelongsTo
    {
        return $this->belongsTo(
            Courses::class,
            'course_id'
        );
    }

    /**
     * Class
     */
    public function classModel(): BelongsTo
    {
        return $this->belongsTo(
            Classes::class,
            'class_id'
        );
    }

    /**
     * Section
     */
    public function section(): BelongsTo
    {
        return $this->belongsTo(
            Section::class,
            'section_id'
        );
    }

    /**
     * User who recorded attendance
     */
    public function recordedBy(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'recorded_by'
        );
    }
}