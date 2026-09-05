<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Staff extends Model
{
    use HasFactory;

    protected $fillable = [
        'department_id',
        'school_id',
        'user_id',
    ];

    public function school() { return $this->belongsTo(School::class, 'school_id'); }
    public function user() { return $this->belongsTo(User::class, 'user_id'); }
    public function department() { return $this->belongsTo(Department::class, 'department_id'); }
    public function instructor() { return $this->hasOne(Instructor::class, 'staff_id'); }
    public function documents() { return $this->hasMany(StaffDocument::class, 'staff_id'); }
    public function courseAssignments() { return $this->hasMany(CourseAssignment::class, 'staff_id'); }
    public function timetables() { return $this->hasMany(Timetable::class, 'staff_id'); }
    public function attendance() { return $this->hasMany(StaffAttendance::class, 'staff_id'); }
    public function leaves() { return $this->hasMany(Leave::class, 'staff_id'); }
    public function bookIssues() { return $this->hasMany(BookIssue::class, 'staff_id'); }
    public function idCards() { return $this->hasMany(IdCard::class, 'staff_id'); }
    public function medicalRecords() { return $this->hasMany(MedicalRecord::class, 'staff_id'); }
}
