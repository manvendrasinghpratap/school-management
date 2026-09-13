<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InvoiceItem extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'invoice_items';

    protected $fillable = [
        'school_id',
        'invoice_id',
        'fee_category_id',
        'description',
        'quantity',
        'amount',
    ];

    protected $casts = [
        'school_id' => 'integer',
        'invoice_id' => 'integer',
        'fee_category_id' => 'integer',
        'quantity' => 'decimal:2',
        'amount' => 'decimal:2',
        'deleted_at' => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class, 'school_id');
    }

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class, 'invoice_id');
    }

    public function feeCategory(): BelongsTo
    {
        return $this->belongsTo(FeeCategory::class, 'fee_category_id');
    }
}