<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentPromotion extends Model
{
    use HasFactory;

    protected $table = 'student_promotions';

    protected $fillable = [
        'school_id',
        'student_id',
        'from_class_id',
        'to_class_id',
        'from_section_id',
        'to_section_id',
        'from_academic_year_id',
        'to_academic_year_id',
        'promotion_date',
        'status',
        'remarks',
        'approved_by',
        'rejected_by',
    ];

    protected function casts(): array
    {
        return [
            'promotion_date' => 'date',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function fromClass(): BelongsTo
    {
        return $this->belongsTo(Classes::class, 'from_class_id');
    }

    public function toClass(): BelongsTo
    {
        return $this->belongsTo(Classes::class, 'to_class_id');
    }

    public function fromSection(): BelongsTo
    {
        return $this->belongsTo(Section::class, 'from_section_id');
    }

    public function toSection(): BelongsTo
    {
        return $this->belongsTo(Section::class, 'to_section_id');
    }

    public function fromAcademicYear(): BelongsTo
    {
        return $this->belongsTo(
            AcademicYears::class,
            'from_academic_year_id'
        );
    }

    public function toAcademicYear(): BelongsTo
    {
        return $this->belongsTo(
            AcademicYears::class,
            'to_academic_year_id'
        );
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'approved_by'
        );
    }

    public function rejectedBy(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'rejected_by'
        );
    }
}