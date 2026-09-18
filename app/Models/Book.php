<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Book extends Model
{
    use SoftDeletes;

    protected $table = 'books';

    protected $fillable = [
        'school_id',
        'isbn',
        'title',
        'author',
        'publisher',
        'category',
        'category_id',
        'quantity',
        'available_quantity',
        'shelf_location',
    ];

    protected $casts = [
        'id' => 'integer',
        'school_id' => 'integer',
        'category_id' => 'integer',
        'quantity' => 'integer',
        'available_quantity' => 'integer',

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
    | Category
    |--------------------------------------------------------------------------
    */

    public function category()
    {
        return $this->belongsTo(
            LibraryCategory::class,
            'category_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Authors
    |--------------------------------------------------------------------------
    */

    public function authors()
    {
        return $this->belongsToMany(
            LibraryAuthor::class,
            'book_author',
            'book_id',
            'author_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Publisher
    |--------------------------------------------------------------------------
    */

    public function publisherRelation()
    {
        return $this->belongsTo(
            LibraryPublisher::class,
            'publisher_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Physical Copies
    |--------------------------------------------------------------------------
    */

    public function copies()
    {
        return $this->hasMany(
            BookCopy::class,
            'book_id'
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
            'book_id'
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
            'book_id'
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
}