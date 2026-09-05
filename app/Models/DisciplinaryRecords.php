<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DisciplinaryRecords extends Model
{
    use HasFactory;

    protected $fillable = [
        'reported_by',
        'student_id',
    ];

    public function student() { return $this->belongsTo(Students::class, 'student_id'); }
}
