<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Results extends Model
{
    use HasFactory;

    protected $fillable = [
        'approved_by',
        'examination_id',
        'student_id',
    ];

    public function examination() { return $this->belongsTo(Examinations::class, 'examination_id'); }
    public function student() { return $this->belongsTo(Students::class, 'student_id'); }
}
