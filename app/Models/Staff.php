<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Staff extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'staff';

    protected $fillable = [
        'school_id',
        'user_id',
        'department_id',
        'staff_number',
        'first_name',
        'middle_name',
        'last_name',
        'photo',
        'date_of_birth',
        'nationality',
        'gender',
        'phone',
        'staff_type',
        'employment_date',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'date_of_birth' => 'date',
            'employment_date' => 'date',
        ];
    }

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function instructor(): HasOne
    {
        return $this->hasOne(Instructor::class);
    }

    public function getFullNameAttribute(): string
    {
        return trim(
            $this->first_name
            . ' '
            . ($this->middle_name ? $this->middle_name . ' ' : '')
            . $this->last_name
        );
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}