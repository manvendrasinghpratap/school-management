<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FeeStructure extends Model
{
    use HasFactory;

    protected $table = 'fee_structures';

    protected $fillable = [
        'school_id',
        'fee_category_id',
        'academic_year_id',
        'class_id',
        'term_id',
        'amount',
        'due_date',
        'is_active',
    ];

    protected $casts = [
        'school_id' => 'integer',
        'fee_category_id' => 'integer',
        'academic_year_id' => 'integer',
        'class_id' => 'integer',
        'term_id' => 'integer',
        'amount' => 'decimal:2',
        'due_date' => 'date',
        'is_active' => 'boolean',
    ];

    /**
     * School owning this fee structure.
     */
    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class, 'school_id');
    }

    /**
     * Fee category.
     */
    public function feeCategory(): BelongsTo
    {
        return $this->belongsTo(FeeCategory::class, 'fee_category_id');
    }

    /**
     * Academic year.
     *
     * Project model is AcademicYears (plural).
     */
    public function academicYear(): BelongsTo
    {
        return $this->belongsTo(AcademicYears::class, 'academic_year_id');
    }

    /**
     * Class.
     *
     * Project model is Classes.
     */
    public function classModel(): BelongsTo
    {
        return $this->belongsTo(Classes::class, 'class_id');
    }

    /**
     * Term.
     *
     * Project model is Terms.
     */
    public function term(): BelongsTo
    {
        return $this->belongsTo(Terms::class, 'term_id');
    }

    /**
     * Student fee records using this structure.
     */
    public function studentFees(): HasMany
    {
        return $this->hasMany(StudentFee::class, 'fee_structure_id');
    }

    /**
     * Installments belonging to this fee structure.
     */
    public function installments(): HasMany
    {
        return $this->hasMany(FeeInstallment::class, 'fee_structure_id');
    }
}