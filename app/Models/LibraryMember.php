<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LibraryMember extends Model
{
    use SoftDeletes;

    protected $table = 'library_members';

    protected $fillable = [
        'school_id',
        'student_id',
        'staff_id',
        'member_number',
        'joined_at',
        'expiry_date',
        'max_books',
        'status',
        'notes',
        'created_by',
    ];

    protected $casts = [
        'id' => 'integer',
        'school_id' => 'integer',
        'student_id' => 'integer',
        'staff_id' => 'integer',
        'created_by' => 'integer',
        'joined_at' => 'date',
        'expiry_date' => 'date',
        'max_books' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | School
    |--------------------------------------------------------------------------
    */

    public function school()
    {
        return $this->belongsTo(
            School::class,
            'school_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Student
    |--------------------------------------------------------------------------
    */

    public function student()
    {
        return $this->belongsTo(
            Student::class,
            'student_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Staff
    |--------------------------------------------------------------------------
    */

    public function staff()
    {
        return $this->belongsTo(
            Staff::class,
            'staff_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Creator
    |--------------------------------------------------------------------------
    */

    public function creator()
    {
        return $this->belongsTo(
            User::class,
            'created_by'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Issues
    |--------------------------------------------------------------------------
    */

    public function issues()
    {
        return $this->hasMany(
            BookIssue::class,
            'library_member_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Reservations
    |--------------------------------------------------------------------------
    */

    public function reservations()
    {
        return $this->hasMany(
            BookReservation::class,
            'library_member_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | School Scope
    |--------------------------------------------------------------------------
    */

    public function scopeForSchool(
        $query,
        int $schoolId
    ) {
        return $query->where(
            $this->getTable() . '.school_id',
            $schoolId
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Holder Type
    |--------------------------------------------------------------------------
    */

    public function getHolderTypeAttribute(): string
    {
        return $this->student_id
            ? 'student'
            : 'staff';
    }

    /*
    |--------------------------------------------------------------------------
    | Holder Name
    |--------------------------------------------------------------------------
    */

    public function getHolderNameAttribute(): string
    {
        if ($this->student) {
            return trim(
                $this->student->first_name .
                ' ' .
                ($this->student->middle_name ?? '') .
                ' ' .
                $this->student->last_name
            );
        }

        if ($this->staff) {
            return trim(
                $this->staff->first_name .
                ' ' .
                ($this->staff->middle_name ?? '') .
                ' ' .
                $this->staff->last_name
            );
        }

        return '—';
    }
}