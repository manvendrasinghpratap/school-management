<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LibraryPublisher extends Model
{
    use SoftDeletes;

    protected $table = 'library_publishers';

    protected $fillable = [
        'school_id',
        'name',
        'address',
        'phone',
        'email',
        'website',
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

    public function scopeForSchool($query, int $schoolId)
    {
        return $query->where(
            $this->getTable() . '.school_id',
            $schoolId
        );
    }
}