<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HostelRoom extends Model
{
    use SoftDeletes;

    protected $table = 'hostel_rooms';

    protected $fillable = [
        'school_id',
        'hostel_id',
        'room_number',
        'floor',
        'room_type',
        'capacity',
        'monthly_fee',
        'status',
    ];

    protected $casts = [
        'id' => 'integer',
        'school_id' => 'integer',
        'hostel_id' => 'integer',
        'capacity' => 'integer',
        'monthly_fee' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function hostel()
    {
        return $this->belongsTo(Hostel::class, 'hostel_id');
    }

    public function beds()
    {
        return $this->hasMany(HostelBed::class, 'room_id');
    }

    public function allocations()
    {
        return $this->hasMany(HostelAllocation::class, 'room_id');
    }

    public function scopeForSchool($query, int $schoolId)
    {
        return $query->where($this->getTable() . '.school_id', $schoolId);
    }
}
