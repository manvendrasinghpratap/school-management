<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Student extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'students';
    protected $fillable = ['school_id','user_id','student_number','admission_number','first_name','middle_name','last_name','photo','date_of_birth','nationality','gender','phone','admission_date','status','address','created_by','updated_by'];
    protected function casts(): array { return ['date_of_birth'=>'date','admission_date'=>'date']; }
    public function school(): BelongsTo { return $this->belongsTo(School::class); }
    public function user(): BelongsTo { return $this->belongsTo(User::class); }
    public function createdBy(): BelongsTo { return $this->belongsTo(User::class,'created_by'); }
    public function updatedBy(): BelongsTo { return $this->belongsTo(User::class,'updated_by'); }
    public function guardians(): BelongsToMany { return $this->belongsToMany(Guardian::class,'student_guardians','student_id','guardian_id')->withPivot(['relationship','is_primary','is_emergency_contact'])->withTimestamps(); }
    public function documents(): HasMany { return $this->hasMany(StudentDocument::class,'student_id'); }
    public function enrollments(): HasMany { return $this->hasMany(StudentEnrollment::class,'student_id')->latest('enrollment_date'); }
    public function promotions(): HasMany { return $this->hasMany(StudentPromotion::class,'student_id'); }
    public function courses(): BelongsToMany { return $this->belongsToMany(Courses::class,'student_courses','student_id','course_id')->withPivot(['academic_year_id','term_id'])->withTimestamps(); }
    public function graduations(): HasMany { return $this->hasMany(Graduation::class,'student_id')->latest('graduation_date'); }
    public function alumni(): HasOne { return $this->hasOne(Alumni::class,'student_id'); }
    public function getFullNameAttribute(): string { return trim($this->first_name.' '.($this->middle_name ? $this->middle_name.' ' : '').$this->last_name); }
    public function scopeActive($query) { return $query->where('status','active'); }
    public function scopeForSchool($query, int $schoolId) { return $query->where('school_id',$schoolId); }
}
