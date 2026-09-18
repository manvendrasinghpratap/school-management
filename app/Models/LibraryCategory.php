<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LibraryCategory extends Model
{
    use SoftDeletes;

    protected $table = 'library_categories';

    protected $fillable = [
        'school_id',
        'name',
        'code',
        'description',
        'created_by',
    ];

    protected $casts = [
        'id'         => 'integer',
        'school_id'  => 'integer',
        'created_by' => 'integer',
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
    | Books
    |--------------------------------------------------------------------------
    */

    public function books()
    {
        return $this->hasMany(
            Book::class,
            'category_id',
            'id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | School Scope
    |--------------------------------------------------------------------------
    */

    public function scopeForSchool($query, int $schoolId)
    {
        return $query->where(
            $this->getTable() . '.school_id',
            $schoolId
        );
    }
}