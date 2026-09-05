<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IdCard extends Model
{
    use HasFactory;

    protected $fillable = [
        'staff_id',
        'student_id',
    ];

    public function student() { return $this->belongsTo(Student::class, 'student_id'); }
    public function staff() { return $this->belongsTo(Staff::class, 'staff_id'); }
}
