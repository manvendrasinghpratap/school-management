<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Assets extends Model
{
    use HasFactory;
    protected $table = 'assets';
    protected $fillable = ['school_id','supplier_id','asset_code','name','category','purchase_date','purchase_cost','location','condition_status','assigned_to','description'];
    protected $casts = ['purchase_date'=>'date','purchase_cost'=>'decimal:2'];
    public function school(): BelongsTo { return $this->belongsTo(School::class); }
    public function supplier(): BelongsTo { return $this->belongsTo(Suppliers::class, 'supplier_id'); }
    public function assignedTo(): BelongsTo { return $this->belongsTo(User::class, 'assigned_to'); }
}
