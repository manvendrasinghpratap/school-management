<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HostelAllocation extends Model
{
    use HasFactory;

    protected $fillable = [
        'hostel_id',
        'room_id',
        'student_id',
    ];

    public function student() { return $this->belongsTo(Student::class, 'student_id'); }
    public function hostel() { return $this->belongsTo(Hostel::class, 'hostel_id'); }
    public function room() { return $this->belongsTo(Room::class, 'room_id'); }
}
