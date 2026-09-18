<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TransportStop extends Model
{
    use SoftDeletes;

    protected $table = 'transport_stops';

    protected $fillable = [
        'school_id',
        'route_id',
        'name',
        'sequence_no',
        'pickup_time',
        'dropoff_time',
        'address',
        'monthly_fee'
    ];

    protected $casts=['monthly_fee'=>'decimal:2'];
public function route(){ return $this->belongsTo(TransportRoute::class,'route_id'); }

    public function scopeForSchool($query, int $schoolId)
    {
        return $query->where($this->getTable().'.school_id', $schoolId);
    }
}
