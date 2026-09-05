<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
class Guardian extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'school_id',
        'guardian_number',
        'title',
        'first_name',
        'middle_name',
        'last_name',
        'address',
        'state',
        'local_government',
        'phone',
        'whatsapp',
        'email',
        'occupation',
    ];

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    public function students(): BelongsToMany
    {
        return $this->belongsToMany(
            Student::class,
            'student_guardians'
        )->withPivot([
            'relationship',
            'is_primary',
            'is_emergency_contact',
        ])->withTimestamps();
    }

    public function getFullNameAttribute(): string
    {
        return trim(
            ($this->title ? $this->title . ' ' : '') .
            $this->first_name . ' ' .
            ($this->middle_name
                ? $this->middle_name . ' '
                : '') .
            $this->last_name
        );
    }
}