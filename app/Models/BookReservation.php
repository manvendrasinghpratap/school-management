<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BookReservation extends Model
{
    use SoftDeletes;

    protected $table = 'book_reservations';

    protected $fillable = [
        'school_id',
        'book_id',
        'library_member_id',
        'reserved_at',
        'expires_at',
        'status',
        'notes',
    ];

    protected $casts = [
        'id' => 'integer',
        'school_id' => 'integer',
        'book_id' => 'integer',
        'library_member_id' => 'integer',
        'reserved_at' => 'datetime',
        'expires_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function school()
    {
        return $this->belongsTo(School::class, 'school_id');
    }

    public function book()
    {
        return $this->belongsTo(Book::class, 'book_id');
    }

    public function member()
    {
        return $this->belongsTo(LibraryMember::class, 'library_member_id');
    }

    public function scopeForSchool($query, int $schoolId)
    {
        return $query->where($this->getTable() . '.school_id', $schoolId);
    }

    public function scopeActive($query)
    {
        return $query->where($this->getTable() . '.status', 'active');
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    public function isExpired(): bool
    {
        return $this->status === 'expired';
    }

    public function isFulfilled(): bool
    {
        return $this->status === 'fulfilled';
    }

    public function isCancelled(): bool
    {
        return $this->status === 'cancelled';
    }
}
