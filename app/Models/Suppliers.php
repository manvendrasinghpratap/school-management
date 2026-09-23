<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Suppliers extends Model
{
    use HasFactory;
    protected $table = 'suppliers';
    protected $fillable = ['school_id','name','contact_person','phone','email','address'];
    public function school(): BelongsTo { return $this->belongsTo(School::class); }
    public function inventoryItems(): HasMany { return $this->hasMany(InventoryItems::class, 'supplier_id'); }
    public function assets(): HasMany { return $this->hasMany(Assets::class, 'supplier_id'); }
}
