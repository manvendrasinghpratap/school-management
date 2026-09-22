<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HostelFee extends Model
{
    use SoftDeletes;

    protected $table = 'hostel_fees';

    protected $fillable = [
        'school_id',
        'hostel_allocation_id',
        'fee_month',
        'amount',
        'status',
        'invoice_id',
        'notes',
    ];

    protected $casts = [
        'id' => 'integer',
        'school_id' => 'integer',
        'hostel_allocation_id' => 'integer',
        'fee_month' => 'date',
        'amount' => 'decimal:2',
        'invoice_id' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function allocation()
    {
        return $this->belongsTo(HostelAllocation::class, 'hostel_allocation_id');
    }

    public function invoice()
    {
        return $this->belongsTo(Invoice::class, 'invoice_id');
    }

    public function scopeForSchool($query, int $schoolId)
    {
        return $query->where($this->getTable() . '.school_id', $schoolId);
    }
}
