<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transcript extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'transcripts';

    protected $fillable = [
        'school_id',
        'student_id',
        'file_path',
        'generated_by',
        'generated_at',
    ];

    protected $casts = [
        'school_id' => 'integer',
        'student_id' => 'integer',
        'generated_by' => 'integer',
        'generated_at' => 'datetime',
    ];

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class, 'school_id');
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    public function generatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'generated_by');
    }
}