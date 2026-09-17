<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventParticipant extends Model
{
    protected $fillable = [
        'event_id',
        'student_id',
        'staff_id',
        'participant_type',
        'role',
        'attendance_status',
    ];

    public function event()
    {
        return $this->belongsTo(
            SchoolEvent::class,
            'event_id'
        );
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function staff()
    {
        return $this->belongsTo(Staff::class);
    }
}