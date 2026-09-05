<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StaffAttendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'recorded_by',
        'staff_id',
    ];

    public function staff() { return $this->belongsTo(Staff::class, 'staff_id'); }
    public function recordedBy() { return $this->belongsTo(User::class, 'recorded_by'); }
}
