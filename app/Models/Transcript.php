<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transcripts extends Model
{
    use HasFactory;

    protected $fillable = [
        'generated_by',
        'student_id',
    ];

    public function student() { return $this->belongsTo(Students::class, 'student_id'); }
}
