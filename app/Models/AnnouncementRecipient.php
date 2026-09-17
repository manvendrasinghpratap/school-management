<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AnnouncementRecipient extends Model
{
    protected $fillable = [
        'announcement_id',
        'user_id',
        'student_id',
        'staff_id',
        'recipient_type',
        'is_read',
        'read_at',
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'read_at' => 'datetime',
    ];

    public function announcement()
    {
        return $this->belongsTo(
            Announcement::class,
            'announcement_id'
        );
    }

    public function user()
    {
        return $this->belongsTo(User::class);
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