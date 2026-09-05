<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Terms extends Model
{
    use HasFactory;

    protected $fillable = [
        'academic_year_id',
    ];

    public function academic_year() { return $this->belongsTo(AcademicYears::class, 'academic_year_id'); }
}
