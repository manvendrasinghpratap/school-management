<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Scholarships extends Model
{
    use HasFactory;

    protected $table = 'scholarships';

    protected $fillable = [
        'school_id',
        'name',
        'type',
        'value',
        'description',
        'is_active',
    ];

    protected $casts = [
        'school_id' => 'integer',
        'value' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class, 'school_id');
    }

    public function studentFees(): HasMany
    {
        return $this->hasMany(StudentFee::class, 'scholarship_id');
    }
}