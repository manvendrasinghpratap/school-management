<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FinanceMovement extends Model
{
    use HasFactory;

    protected $table = 'finance_movements';

    protected $fillable = [
        'school_id',
        'student_id',
        'guardian_id',
        'invoice_id',
        'invoice_item_id',
        'payment_id',
        'receipt_id',
        'movement_type',
        'reference_type',
        'reference_id',
        'amount',
        'direction',
        'previous_balance',
        'new_balance',
        'performed_by',
        'reason',
        'notes',
    ];

    protected $casts = [
        'school_id' => 'integer',
        'student_id' => 'integer',
        'guardian_id' => 'integer',
        'invoice_id' => 'integer',
        'invoice_item_id' => 'integer',
        'payment_id' => 'integer',
        'receipt_id' => 'integer',
        'reference_id' => 'integer',
        'amount' => 'decimal:2',
        'previous_balance' => 'decimal:2',
        'new_balance' => 'decimal:2',
        'performed_by' => 'integer',
    ];

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class, 'school_id');
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    public function guardian(): BelongsTo
    {
        return $this->belongsTo(Guardian::class, 'guardian_id');
    }

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class, 'invoice_id');
    }

    public function invoiceItem(): BelongsTo
    {
        return $this->belongsTo(InvoiceItem::class, 'invoice_item_id');
    }

    public function payment(): BelongsTo
    {
        return $this->belongsTo(Payment::class, 'payment_id');
    }

    public function receipt(): BelongsTo
    {
        return $this->belongsTo(Receipt::class, 'receipt_id');
    }

    public function performedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'performed_by');
    }
}