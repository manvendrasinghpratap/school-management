<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Classes extends Model
{
    use HasFactory;

    protected $fillable = [
        'department_id',
        'level_id',
        'school_id',
    ];

    public function department() { return $this->belongsTo(Departments::class, 'department_id'); }
    public function level() { return $this->belongsTo(Levels::class, 'level_id'); }
    public function school() { return $this->belongsTo(Schools::class, 'school_id'); }
}
