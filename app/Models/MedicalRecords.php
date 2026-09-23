<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MedicalRecords extends Model
{
    use HasFactory;
    protected $table = 'medical_records';
    protected $fillable = ['school_id','student_id','staff_id','record_date','condition_name','description','treatment','emergency_contact','emergency_phone','recorded_by'];
    protected $casts = ['record_date'=>'date'];
    public function school(): BelongsTo { return $this->belongsTo(School::class); }
    public function student(): BelongsTo { return $this->belongsTo(Student::class); }
    public function staff(): BelongsTo { return $this->belongsTo(Staff::class); }
    public function recordedBy(): BelongsTo { return $this->belongsTo(User::class, 'recorded_by'); }
}
