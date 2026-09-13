<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class ReportCard extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'report_cards';

    protected $fillable = [
        'school_id',
        'student_id',
        'examination_id',
        'file_path',
        'status',
        'generated_by',
        'published_at',
    ];

    protected $casts = [
        'school_id' => 'integer',
        'student_id' => 'integer',
        'examination_id' => 'integer',
        'generated_by' => 'integer',
        'published_at' => 'datetime',
    ];

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class, 'school_id');
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    public function examination(): BelongsTo
    {
        return $this->belongsTo(Examination::class, 'examination_id');
    }

    public function generatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'generated_by');
    }
}