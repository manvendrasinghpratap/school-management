<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InventoryItems extends Model
{
    use HasFactory;
    protected $table = 'inventory_items';
    protected $fillable = ['school_id','supplier_id','name','item_code','category','quantity','unit','reorder_level','unit_cost','description'];
    protected $casts = ['quantity'=>'decimal:2','reorder_level'=>'decimal:2','unit_cost'=>'decimal:2'];
    public function school(): BelongsTo { return $this->belongsTo(School::class); }
    public function supplier(): BelongsTo { return $this->belongsTo(Suppliers::class, 'supplier_id'); }
}
