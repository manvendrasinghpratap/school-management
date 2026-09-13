<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FeeInstallment extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'fee_installments';

    protected $fillable = [
        'school_id',
        'fee_structure_id',
        'installment_number',
        'name',
        'amount',
        'due_date',
        'status',
    ];

    protected $casts = [
        'school_id' => 'integer',
        'fee_structure_id' => 'integer',
        'installment_number' => 'integer',
        'amount' => 'decimal:2',
        'due_date' => 'date',
    ];

    /**
     * School owning this installment.
     */
    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class, 'school_id');
    }

    /**
     * Parent fee structure.
     */
    public function feeStructure(): BelongsTo
    {
        return $this->belongsTo(FeeStructure::class, 'fee_structure_id');
    }
}