<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Result extends Model
{
    use HasFactory;

    protected $table = 'results';

    protected $fillable = [
        'student_id',
        'examination_id',
        'total_score',
        'average',
        'grade',
        'position',
        'status',
        'approved_by',
        'published_by',
    ];

    protected $casts = [
        'student_id' => 'integer',
        'examination_id' => 'integer',
        'total_score' => 'decimal:2',
        'average' => 'decimal:2',
        'position' => 'integer',
        'approved_by' => 'integer',
        'published_by' => 'integer',
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

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'approved_by'
        );
    }

    public function publishedBy(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'published_by'
        );
    }
}