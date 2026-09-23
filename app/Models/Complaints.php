<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Complaints extends Model
{
    use HasFactory;
    protected $table = 'complaints';
    protected $fillable = ['school_id','student_id','user_id','subject','description','priority','status','assigned_to','resolved_at'];
    protected $casts = ['resolved_at'=>'datetime'];
    public function school(): BelongsTo { return $this->belongsTo(School::class); }
    public function student(): BelongsTo { return $this->belongsTo(Student::class); }
    public function submittedBy(): BelongsTo { return $this->belongsTo(User::class, 'user_id'); }
    public function assignedTo(): BelongsTo { return $this->belongsTo(User::class, 'assigned_to'); }
}
