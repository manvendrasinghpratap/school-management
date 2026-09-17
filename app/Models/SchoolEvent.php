<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SchoolEvent extends Model
{
    use SoftDeletes;

    protected $table = 'events';

    protected $fillable = [
        'school_id',
        'created_by',
        'title',
        'event_type',
        'description',
        'start_datetime',
        'end_datetime',
        'location',
        'status',
        'is_public',
    ];

    protected $casts = [
        'start_datetime' => 'datetime',
        'end_datetime' => 'datetime',
        'is_public' => 'boolean',
    ];

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function participants()
    {
        return $this->hasMany(EventParticipant::class, 'event_id');
    }
}