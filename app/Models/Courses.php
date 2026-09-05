<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Courses extends Model
{
    use HasFactory;

    protected $fillable = [
        'department_id',
        'school_id',
    ];

    public function department() { return $this->belongsTo(Departments::class, 'department_id'); }
    public function school() { return $this->belongsTo(Schools::class, 'school_id'); }
}
