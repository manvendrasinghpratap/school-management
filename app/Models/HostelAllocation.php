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
        'created_by'
    ];

    protected $casts=['start_date'=>'date','end_date'=>'date','monthly_fee'=>'decimal:2'];
public function hostel(){ return $this->belongsTo(Hostel::class); }
public function room(){ return $this->belongsTo(HostelRoom::class,'room_id'); }
public function bed(){ return $this->belongsTo(HostelBed::class,'bed_id'); }
public function student(){ return $this->belongsTo(Student::class); }
public function academicYear(){ return $this->belongsTo(AcademicYears::class,'academic_year_id'); }

    public function scopeForSchool($query, int $schoolId)
    {
        return $query->where($this->getTable().'.school_id', $schoolId);
    }
}
