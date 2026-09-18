<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vehicle extends Model
{
    use SoftDeletes;

    protected $table = 'vehicles';

    protected $fillable = [
        'school_id',
        'registration_number',
        'vehicle_number',
        'vehicle_type',
        'make',
        'model',
        'year',
        'capacity',
        'driver_id',
        'insurance_expiry',
        'fitness_expiry',
        'status',
        'notes',
        'created_by'
    ];

    protected $casts=['insurance_expiry'=>'date','fitness_expiry'=>'date'];
public function driver(){ return $this->belongsTo(TransportDriver::class,'driver_id'); }
public function routes(){ return $this->hasMany(TransportRoute::class); }

    public function scopeForSchool($query, int $schoolId)
    {
        return $query->where($this->getTable().'.school_id', $schoolId);
    }
}
