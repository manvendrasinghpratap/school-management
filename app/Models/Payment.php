<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model
{
    use HasFactory, SoftDeletes;

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
        'school_id'   => 'integer',
        'invoice_id'  => 'integer',
        'amount'      => 'decimal:2',
        'paid_at'     => 'datetime',
        'received_by' => 'integer',
        'deleted_at'  => 'datetime',
    ];

    /**
     * School that received the payment.
     */
    public function school(): BelongsTo
    {
        return $this->belongsTo(
            School::class,
            'school_id'
        );
    }

    /**
     * Invoice against which the payment was recorded.
     */
    public function invoice(): BelongsTo
    {
        return $this->belongsTo(
            Invoice::class,
            'invoice_id'
        );
    }

    /**
     * User who received/recorded the payment.
     */
    public function receivedBy(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'received_by'
        );
    }

    /**
     * Payment allocations.
     */
    public function allocations(): HasMany
    {
        return $this->hasMany(
            PaymentAllocation::class,
            'payment_id'
        );
    }

    /**
     * Payment refund/reversal requests.
     */
    public function refunds(): HasMany
    {
        return $this->hasMany(
            PaymentRefund::class,
            'payment_id'
        );
    }

    /**
     * Refunds which currently reserve part of this payment.
     *
     * Requested, approved and processed refunds are included.
     */
    public function activeRefunds(): HasMany
    {
        return $this->hasMany(
            PaymentRefund::class,
            'payment_id'
        )->whereIn('status', [
            'requested',
            'approved',
            'processed',
        ]);
    }

    /**
     * Determine whether this payment has an active
     * refund request.
     */
    public function hasActiveRefundRequest(): bool
    {
        return $this->refunds()
            ->whereIn('status', [
                'requested',
                'approved',
                'processed',
            ])
            ->exists();
    }

    /**
     * Get the latest active refund request.
     */
    public function latestActiveRefund(): ?PaymentRefund
    {
        return $this->refunds()
            ->whereIn('status', [
                'requested',
                'approved',
                'processed',
            ])
            ->latest('id')
            ->first();
    }

    /**
     * Get total amount reserved or already refunded.
     */
    public function activeRefundAmount(): float
    {
        return round(
            (float) $this->refunds()
                ->whereIn('status', [
                    'requested',
                    'approved',
                    'processed',
                ])
                ->sum('amount'),
            2
        );
    }

    /**
     * Get total amount actually processed/refunded.
     */
    public function processedRefundAmount(): float
    {
        return round(
            (float) $this->refunds()
                ->where('status', 'processed')
                ->sum('amount'),
            2
        );
    }

    /**
     * Get amount still available for refund.
     */
    public function remainingRefundableAmount(): float
    {
        return max(
            round(
                (float) $this->amount
                - $this->activeRefundAmount(),
                2
            ),
            0
        );
    }

    /**
     * Determine whether a refund request can currently
     * be created for this payment.
     */
    public function canRequestRefund(): bool
    {
        if ($this->trashed()) {
            return false;
        }

        return $this->remainingRefundableAmount() > 0;
    }

    /**
     * Get the current active refund status.
     */
    public function refundStatus(): ?string
    {
        $refund = $this->latestActiveRefund();

        if (!$refund) {
            return null;
        }

        return match ($refund->status) {
            'requested' => 'Refund Requested',
            'approved'  => 'Refund Approved',
            'processed' => 'Refunded',
            default     => null,
        };
    }
}