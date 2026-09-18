<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BookCopy extends Model
{
    use SoftDeletes;

    protected $table = 'book_copies';

    protected $fillable = [
        'school_id',
        'book_id',
        'accession_number',
        'barcode',
        'purchase_date',
        'purchase_price',
        'condition_status',
        'status',
        'notes',
    ];

    protected $casts = [
        'id'            => 'integer',
        'school_id'     => 'integer',
        'book_id'       => 'integer',
        'purchase_date' => 'date',
        'purchase_price'=> 'decimal:2',
        'created_at'    => 'datetime',
        'updated_at'    => 'datetime',
        'deleted_at'    => 'datetime',
    ];

    /**
     * School.
     */
    public function school()
    {
        return $this->belongsTo(
            School::class,
            'school_id'
        );
    }

    /**
     * Book.
     */
    public function book()
    {
        return $this->belongsTo(
            Book::class,
            'book_id'
        );
    }

    /**
     * Scope records to a school.
     */
    public function scopeForSchool($query, int $schoolId)
    {
        return $query->where(
            $this->getTable() . '.school_id',
            $schoolId
        );
    }
}