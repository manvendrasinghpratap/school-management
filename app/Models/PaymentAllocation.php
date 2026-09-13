<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PaymentAllocation extends Model
{
    use HasFactory;

    protected $table = 'payment_allocations';

    protected $fillable = [
        'school_id',
        'payment_id',
        'invoice_id',
        'invoice_item_id',
        'amount',
        'allocated_at',
        'allocated_by',
    ];

    protected $casts = [
        'school_id' => 'integer',
        'payment_id' => 'integer',
        'invoice_id' => 'integer',
        'invoice_item_id' => 'integer',
        'amount' => 'decimal:2',
        'allocated_at' => 'datetime',
        'allocated_by' => 'integer',
    ];

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class, 'school_id');
    }

    public function payment(): BelongsTo
    {
        return $this->belongsTo(Payment::class, 'payment_id');
    }

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class, 'invoice_id');
    }

    public function invoiceItem(): BelongsTo
    {
        return $this->belongsTo(InvoiceItem::class, 'invoice_item_id');
    }

    public function allocatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'allocated_by');
    }
}