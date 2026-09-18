<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TransportDriver extends Model
{
    use SoftDeletes;

    protected $table = 'transport_drivers';

    protected $fillable = [
        'school_id',
        'staff_id',
        'name',
        'license_number',
        'license_expiry',
        'phone',
        'address',
        'status'
    ];

    protected $casts=['license_expiry'=>'date'];
public function staff(){ return $this->belongsTo(Staff::class); }
public function vehicles(){ return $this->hasMany(Vehicle::class,'driver_id'); }
public function routes(){ return $this->hasMany(TransportRoute::class,'driver_id'); }

    public function scopeForSchool($query, int $schoolId)
    {
        return $query->where($this->getTable().'.school_id', $schoolId);
    }
}
