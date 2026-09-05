<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentFee extends Model
{
    use HasFactory;

    protected $fillable = [
        'fee_structure_id',
        'scholarship_id',
        'student_id',
    ];

    public function student() { return $this->belongsTo(Student::class, 'student_id'); }
    public function feeStructure() { return $this->belongsTo(FeeStructure::class, 'fee_structure_id'); }
    public function scholarship() { return $this->belongsTo(Scholarship::class, 'scholarship_id'); }
}
