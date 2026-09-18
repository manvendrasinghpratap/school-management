<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Hostel extends Model
{
    use SoftDeletes;

    protected $table = 'hostels';

    protected $fillable = [
        'school_id',
        'name',
        'code',
        'hostel_type',
        'address',
        'warden_staff_id',
        'capacity',
        'monthly_fee',
        'status',
        'created_by'
    ];

    protected $casts=['monthly_fee'=>'decimal:2'];
public function warden(){ return $this->belongsTo(Staff::class,'warden_staff_id'); }
public function rooms(){ return $this->hasMany(HostelRoom::class); }
public function allocations(){ return $this->hasMany(HostelAllocation::class); }

    public function scopeForSchool($query, int $schoolId)
    {
        return $query->where($this->getTable().'.school_id', $schoolId);
    }
}
