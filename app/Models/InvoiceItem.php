<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'fee_category_id',
        'invoice_id',
    ];

    public function invoice() { return $this->belongsTo(Invoice::class, 'invoice_id'); }
    public function feeCategory() { return $this->belongsTo(FeeCategory::class, 'fee_category_id'); }
}
