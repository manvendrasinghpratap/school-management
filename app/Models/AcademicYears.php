<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class AcademicYears extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'academic_years';

    protected $fillable = [
        'school_id',
        'name',
        'start_date',
        'end_date',
        'is_current',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date'   => 'date',
            'is_current' => 'boolean',
            'is_active'  => 'boolean',
        ];
    }

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class, 'school_id');
    }

    public function terms(): HasMany
    {
        return $this->hasMany(Terms::class, 'academic_year_id');
    }
}