<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeeStructure extends Model
{
    use HasFactory;

    protected $fillable = [
        'academic_year_id',
        'class_id',
        'fee_category_id',
    ];

    public function feeCategory() { return $this->belongsTo(FeeCategory::class, 'fee_category_id'); }
    public function academicYear() { return $this->belongsTo(AcademicYear::class, 'academic_year_id'); }
    public function classModel() { return $this->belongsTo(ClassModel::class, 'class_id'); }
    public function studentFees() { return $this->hasMany(StudentFee::class, 'fee_structure_id'); }
}
