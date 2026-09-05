<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeeCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_id',
    ];

    public function school() { return $this->belongsTo(School::class, 'school_id'); }
    public function feeStructures() { return $this->hasMany(FeeStructure::class, 'fee_category_id'); }
    public function invoiceItems() { return $this->hasMany(InvoiceItem::class, 'fee_category_id'); }
}
