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
        'description',
        'warden_staff_id',
        'capacity',
        'monthly_fee',
        'status',
        'created_by',
    ];

    protected $casts = [
        'id' => 'integer',
        'school_id' => 'integer',
        'warden_staff_id' => 'integer',
        'capacity' => 'integer',
        'monthly_fee' => 'decimal:2',
        'created_by' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function warden()
    {
        return $this->belongsTo(Staff::class, 'warden_staff_id');
    }

    public function rooms()
    {
        return $this->hasMany(HostelRoom::class, 'hostel_id');
    }

    public function allocations()
    {
        return $this->hasMany(HostelAllocation::class, 'hostel_id');
    }

    public function scopeForSchool($query, int $schoolId)
    {
        return $query->where($this->getTable() . '.school_id', $schoolId);
    }
}
