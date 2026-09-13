<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentFee extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'student_fees';

    protected $fillable = [
        'school_id',
        'student_id',
        'fee_structure_id',
        'scholarship_id',
        'amount',
        'discount',
        'status',
    ];

    protected $casts = [
        'school_id' => 'integer',
        'student_id' => 'integer',
        'fee_structure_id' => 'integer',
        'scholarship_id' => 'integer',
        'amount' => 'decimal:2',
        'discount' => 'decimal:2',
    ];

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class, 'school_id');
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    public function feeStructure(): BelongsTo
    {
        return $this->belongsTo(FeeStructure::class, 'fee_structure_id');
    }

    public function scholarship(): BelongsTo
    {
        return $this->belongsTo(Scholarships::class, 'scholarship_id');
    }
}