<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Payment extends Model
{
    use HasFactory;

    protected $table = 'payments';

    protected $fillable = [
        'school_id',
        'invoice_id',
        'amount',
        'payment_method',
        'transaction_reference',
        'paid_at',
        'received_by',
        'notes',
    ];

    protected $casts = [
        'school_id' => 'integer',
        'invoice_id' => 'integer',
        'amount' => 'decimal:2',
        'paid_at' => 'datetime',
        'received_by' => 'integer',
    ];

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class, 'school_id');
    }

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class, 'invoice_id');
    }

    public function receivedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'received_by');
    }

    public function receipt(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Receipt::class, 'payment_id');
    }

    public function allocations(): HasMany
    {
        return $this->hasMany(PaymentAllocation::class, 'payment_id');
    }

    public function refunds(): HasMany
    {
        return $this->hasMany(PaymentRefund::class, 'payment_id');
    }
}