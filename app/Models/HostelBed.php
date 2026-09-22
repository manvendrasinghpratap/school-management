<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HostelBed extends Model
{
    use SoftDeletes;

    protected $table = 'hostel_beds';

    protected $fillable = [
        'school_id',
        'room_id',
        'bed_number',
        'status',
    ];

    protected $casts = [
        'id' => 'integer',
        'school_id' => 'integer',
        'room_id' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function room()
    {
        return $this->belongsTo(HostelRoom::class, 'room_id');
    }

    public function allocations()
    {
        return $this->hasMany(HostelAllocation::class, 'bed_id');
    }

    public function scopeForSchool($query, int $schoolId)
    {
        return $query->where($this->getTable() . '.school_id', $schoolId);
    }
}
