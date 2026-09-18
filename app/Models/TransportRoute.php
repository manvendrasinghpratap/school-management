<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TransportRoute extends Model
{
    use SoftDeletes;

    protected $table = 'transport_routes';

    protected $fillable = [
        'school_id',
        'vehicle_id',
        'driver_id',
        'name',
        'code',
        'start_point',
        'end_point',
        'departure_time',
        'arrival_time',
        'monthly_fee',
        'status',
        'notes',
        'created_by'
    ];

    protected $casts=['monthly_fee'=>'decimal:2'];
public function vehicle(){ return $this->belongsTo(Vehicle::class); }
public function driver(){ return $this->belongsTo(TransportDriver::class,'driver_id'); }
public function stops(){ return $this->hasMany(TransportStop::class,'route_id')->orderBy('sequence_no'); }
public function students(){ return $this->hasMany(RouteStudent::class,'route_id'); }

    public function scopeForSchool($query, int $schoolId)
    {
        return $query->where($this->getTable().'.school_id', $schoolId);
    }
}
