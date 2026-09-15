<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PaymentRefund extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'payment_refunds';

    protected $fillable = [
        'school_id',
        'payment_id',
        'student_id',
        'amount',
        'reason',
        'status',
        'requested_by',
        'approved_by',
        'processed_by',
        'requested_at',
        'approved_at',
        'processed_at',
        'reference',
        'notes',
    ];

    protected $casts = [
        'school_id' => 'integer',
        'payment_id' => 'integer',
        'student_id' => 'integer',
        'amount' => 'decimal:2',
        'requested_by' => 'integer',
        'approved_by' => 'integer',
        'processed_by' => 'integer',
        'requested_at' => 'datetime',
        'approved_at' => 'datetime',
        'processed_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function school(): BelongsTo
    {
        return $this->belongsTo(
            School::class,
            'school_id'
        );
    }

    public function payment(): BelongsTo
    {
        return $this->belongsTo(
            Payment::class,
            'payment_id'
        );
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(
            Student::class,
            'student_id'
        );
    }

    public function requester(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'requested_by'
        );
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'approved_by'
        );
    }

    public function processor(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'processed_by'
        );
    }
}