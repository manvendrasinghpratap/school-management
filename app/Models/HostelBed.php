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
        'status'
    ];

    public function room(){ return $this->belongsTo(HostelRoom::class,'room_id'); }
public function allocations(){ return $this->hasMany(HostelAllocation::class,'bed_id'); }

    public function scopeForSchool($query, int $schoolId)
    {
        return $query->where($this->getTable().'.school_id', $schoolId);
    }
}
