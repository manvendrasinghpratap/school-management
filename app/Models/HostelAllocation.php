<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HostelAllocation extends Model
{
    use SoftDeletes;

    protected $table = 'hostel_allocations';

    protected $fillable = [
        'school_id',
        'hostel_id',
        'room_id',
        'bed_id',
        'student_id',
        'academic_year_id',
        'start_date',
        'end_date',
        'monthly_fee',
        'status',
        'notes',
        'created_by',
    ];

    protected $casts = [
        'id' => 'integer',
        'school_id' => 'integer',
        'hostel_id' => 'integer',
        'room_id' => 'integer',
        'bed_id' => 'integer',
        'student_id' => 'integer',
        'academic_year_id' => 'integer',
        'start_date' => 'date',
        'end_date' => 'date',
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

    public function hostel()
    {
        return $this->belongsTo(Hostel::class, 'hostel_id');
    }

    public function room()
    {
        return $this->belongsTo(HostelRoom::class, 'room_id');
    }

    public function bed()
    {
        return $this->belongsTo(HostelBed::class, 'bed_id');
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function academicYear()
    {
        return $this->belongsTo(AcademicYears::class, 'academic_year_id');
    }

    public function fees()
    {
        return $this->hasMany(HostelFee::class, 'hostel_allocation_id');
    }

    public function scopeForSchool($query, int $schoolId)
    {
        return $query->where($this->getTable() . '.school_id', $schoolId);
    }

    public function isActive(): bool
    {
        return in_array($this->status, ['allocated', 'checked_in'], true);
    }
}
