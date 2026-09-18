<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LibraryAuthor extends Model
{
    use SoftDeletes;

    protected $table = 'library_authors';

    protected $fillable = [
        'school_id',
        'name',
        'bio',
        'created_by',
    ];

    protected $casts = [
        'id' => 'integer',
        'school_id' => 'integer',
        'created_by' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function school()
    {
        return $this->belongsTo(
            School::class,
            'school_id'
        );
    }

    public function creator()
    {
        return $this->belongsTo(
            User::class,
            'created_by'
        );
    }

    public function books()
    {
        return $this->belongsToMany(
            Book::class,
            'book_author',
            'author_id',
            'book_id'
        );
    }

    public function scopeForSchool($query, int $schoolId)
    {
        return $query->where(
            $this->getTable() . '.school_id',
            $schoolId
        );
    }
}