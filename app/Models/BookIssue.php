<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BookIssue extends Model
{
    use SoftDeletes;

    protected $table = 'book_issues';

    protected $fillable = [
        'school_id',
        'library_member_id',
        'book_id',
        'book_copy_id',
        'student_id',
        'staff_id',
        'issued_date',
        'due_date',
        'due_at',
        'returned_date',
        'returned_at',
        'status',
        'fine',
        'issued_by',
    ];

    protected $casts = [
        'id'                => 'integer',
        'school_id'         => 'integer',
        'library_member_id'  => 'integer',
        'book_id'           => 'integer',
        'book_copy_id'      => 'integer',
        'student_id'        => 'integer',
        'staff_id'          => 'integer',
        'issued_by'         => 'integer',

        'issued_date'       => 'date',
        'due_date'          => 'date',
        'due_at'            => 'datetime',
        'returned_date'     => 'date',
        'returned_at'      => 'datetime',

        'fine'              => 'decimal:2',

        'created_at'        => 'datetime',
        'updated_at'        => 'datetime',
        'deleted_at'        => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function school()
    {
        return $this->belongsTo(School::class, 'school_id');
    }

    public function member()
    {
        return $this->belongsTo(
            LibraryMember::class,
            'library_member_id'
        );
    }

    public function book()
    {
        return $this->belongsTo(
            Book::class,
            'book_id'
        );
    }

    public function copy()
    {
        return $this->belongsTo(
            BookCopy::class,
            'book_copy_id'
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

    public function issuer()
    {
        return $this->belongsTo(
            User::class,
            'issued_by'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

    public function scopeForSchool($query, int $schoolId)
    {
        return $query->where(
            $this->getTable() . '.school_id',
            $schoolId
        );
    }

    public function scopeIssued($query)
    {
        return $query->where(
            $this->getTable() . '.status',
            'issued'
        );
    }

    public function scopeReturned($query)
    {
        return $query->where(
            $this->getTable() . '.status',
            'returned'
        );
    }

    public function scopeOverdue($query)
    {
        return $query->where(
            $this->getTable() . '.status',
            'overdue'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------
    */

    public function isIssued(): bool
    {
        return $this->status === 'issued';
    }

    public function isReturned(): bool
    {
        return $this->status === 'returned';
    }

    public function isOverdue(): bool
    {
        return $this->status === 'overdue';
    }

    public function hasFine(): bool
    {
        return (float) $this->fine > 0;
    }
}