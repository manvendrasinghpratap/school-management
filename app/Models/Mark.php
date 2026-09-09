<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Mark extends Model
{
    use HasFactory;

    protected $table = 'marks';

    protected $fillable = [
        'student_id',
        'examination_id',
        'course_id',
        'score',
        'maximum_score',
        'grade',
        'status',
        'entered_by',
        'approved_by',
    ];

    protected $casts = [
        'student_id' => 'integer',
        'examination_id' => 'integer',
        'course_id' => 'integer',
        'score' => 'decimal:2',
        'maximum_score' => 'decimal:2',
        'entered_by' => 'integer',
        'approved_by' => 'integer',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(
            Student::class,
            'student_id'
        );
    }

    public function examination(): BelongsTo
    {
        return $this->belongsTo(
            Examination::class,
            'examination_id'
        );
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(
            Courses::class,
            'course_id'
        );
    }

    public function enteredBy(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'entered_by'
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