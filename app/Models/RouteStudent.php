<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RouteStudent extends Model
{
    use SoftDeletes;

    protected $table = 'route_students';

    protected $fillable = [
        'school_id',
        'route_id',
        'stop_id',
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
public function route(){ return $this->belongsTo(TransportRoute::class,'route_id'); }
public function stop(){ return $this->belongsTo(TransportStop::class,'stop_id'); }
public function student(){ return $this->belongsTo(Student::class); }
public function academicYear(){ return $this->belongsTo(AcademicYears::class,'academic_year_id'); }

    public function scopeForSchool($query, int $schoolId)
    {
        return $query->where($this->getTable().'.school_id', $schoolId);
    }
}
