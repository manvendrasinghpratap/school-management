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
        'notes'
    ];

    protected $casts=['fee_month'=>'date','amount'=>'decimal:2'];
public function assignment(){ return $this->belongsTo(RouteStudent::class,'route_student_id'); }
public function invoice(){ return $this->belongsTo(Invoice::class,'invoice_id'); }

    public function scopeForSchool($query, int $schoolId)
    {
        return $query->where($this->getTable().'.school_id', $schoolId);
    }
}
