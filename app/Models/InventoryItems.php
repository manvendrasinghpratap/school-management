<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryItems extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_id',
        'supplier_id',
    ];

    public function school() { return $this->belongsTo(Schools::class, 'school_id'); }
    public function supplier() { return $this->belongsTo(Suppliers::class, 'supplier_id'); }
}
