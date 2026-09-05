<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Certificates extends Model
{
    use HasFactory;

    protected $fillable = [
        'issued_by',
        'student_id',
    ];

    public function student() { return $this->belongsTo(Students::class, 'student_id'); }
}
