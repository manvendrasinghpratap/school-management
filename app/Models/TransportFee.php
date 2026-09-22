<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TransportFee extends Model
{
    use SoftDeletes;

    protected $table = 'transport_fees';

    protected $fillable = [
        'school_id',
        'route_student_id',
        'fee_month',
        'amount',
        'status',
        'invoice_id',
        'notes',
    ];

    protected $casts = [
        'id' => 'integer',
        'school_id' => 'integer',
        'route_student_id' => 'integer',
        'fee_month' => 'date',
        'amount' => 'decimal:2',
        'invoice_id' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function school()
    {
        return $this->belongsTo(School::class, 'school_id');
    }

    public function routeStudent()
    {
        return $this->belongsTo(RouteStudent::class, 'route_student_id');
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
