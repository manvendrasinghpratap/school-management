<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Grade extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'grades';

    protected $fillable = [
        'school_id',
        'name',
        'code',
        'minimum_score',
        'maximum_score',
        'grade_point',
        'result',
        'remark',
    ];

    protected $casts = [
        'school_id' => 'integer',
        'minimum_score' => 'decimal:2',
        'maximum_score' => 'decimal:2',
        'grade_point' => 'decimal:2',
    ];

    /**
     * School this grade belongs to.
     */
    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class, 'school_id');
    }
}