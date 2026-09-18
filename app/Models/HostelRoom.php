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
        'status'
    ];

    protected $casts=['monthly_fee'=>'decimal:2'];
public function hostel(){ return $this->belongsTo(Hostel::class); }
public function beds(){ return $this->hasMany(HostelBed::class,'room_id'); }
public function allocations(){ return $this->hasMany(HostelAllocation::class,'room_id'); }

    public function scopeForSchool($query, int $schoolId)
    {
        return $query->where($this->getTable().'.school_id', $schoolId);
    }
}
