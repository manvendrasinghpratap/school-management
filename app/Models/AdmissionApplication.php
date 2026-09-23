<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AdmissionApplication extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'school_id','application_number','first_name','middle_name','last_name',
        'date_of_birth','gender','phone','email','address','guardian_name',
        'guardian_phone','guardian_email','guardian_relationship','academic_year_id',
        'class_id','section_id','status','student_id','applied_at','processed_at',
        'created_by','notes',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'applied_at' => 'datetime',
        'processed_at' => 'datetime',
    ];

    public function school(): BelongsTo { return $this->belongsTo(School::class); }
    public function academicYear(): BelongsTo { return $this->belongsTo(AcademicYears::class, 'academic_year_id'); }
    public function class(): BelongsTo { return $this->belongsTo(Classes::class, 'class_id'); }
    public function section(): BelongsTo { return $this->belongsTo(Section::class, 'section_id'); }
    public function student(): BelongsTo { return $this->belongsTo(Student::class); }
    public function createdBy(): BelongsTo { return $this->belongsTo(User::class, 'created_by'); }

    public function getFullNameAttribute(): string
    {
        return trim($this->first_name.' '.($this->middle_name ? $this->middle_name.' ' : '').$this->last_name);
    }
}
