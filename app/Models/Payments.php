<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payments extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_id',
        'received_by',
    ];

    public function invoice() { return $this->belongsTo(Invoices::class, 'invoice_id'); }
}
