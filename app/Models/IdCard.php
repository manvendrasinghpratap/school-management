<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class IdCard extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'school_id',
        'template_id',
        'student_id',
        'staff_id',
        'card_number',
        'issued_at',
        'expires_at',
        'file_path',
        'verification_code',
        'status',
        'generated_by',
    ];

    protected $casts = [
        'issued_at' => 'date',
        'expires_at' => 'date',
    ];

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function template()
    {
        return $this->belongsTo(
            IdCardTemplate::class,
            'template_id'
        );
    }

    public function student()
    {
        return $this->belongsTo(
            Student::class,
            'student_id'
        );
    }

    public function staff()
    {
        return $this->belongsTo(
            Staff::class,
            'staff_id'
        );
    }

    public function generator()
    {
        return $this->belongsTo(
            User::class,
            'generated_by'
        );
    }

    public function getHolderNameAttribute()
    {
        if ($this->student) {
            return trim(
                $this->student->first_name . ' ' .
                ($this->student->middle_name ?? '') . ' ' .
                $this->student->last_name
            );
        }

        if ($this->staff) {
            return trim(
                $this->staff->first_name . ' ' .
                ($this->staff->middle_name ?? '') . ' ' .
                $this->staff->last_name
            );
        }

        return 'Unknown';
    }
}