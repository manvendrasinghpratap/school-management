<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Certificate extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'school_id',
        'template_id',
        'student_id',
        'certificate_type',
        'certificate_number',
        'recipient_name',
        'course_or_class',
        'issued_at',
        'issue_date',
        'file_path',
        'notes',
        'issued_by',
    ];

    protected $casts = [
        'issued_at' => 'date',
        'issue_date' => 'date',
    ];

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function template()
    {
        return $this->belongsTo(
            CertificateTemplate::class,
            'template_id'
        );
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function issuer()
    {
        return $this->belongsTo(
            User::class,
            'issued_by'
        );
    }

    public function scopeForSchool($query, int $schoolId)
    {
        return $query->where('school_id', $schoolId);
    }
}