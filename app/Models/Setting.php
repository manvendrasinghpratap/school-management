<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Setting extends Model
{
    protected $fillable = [
        'school_id',
        'setting_key',
        'setting_value',
        'setting_type',
    ];

    protected $casts = [
        'school_id' => 'integer',
    ];

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class, 'school_id');
    }

    /**
     * Get the setting value converted to its configured type.
     */
    public function getTypedValueAttribute(): mixed
    {
        return match ($this->setting_type) {
            'boolean' => filter_var(
                $this->setting_value,
                FILTER_VALIDATE_BOOLEAN
            ),

            'integer' => (int) $this->setting_value,

            'float' => (float) $this->setting_value,

            'json' => is_array($this->setting_value)
                ? $this->setting_value
                : json_decode($this->setting_value, true),

            default => $this->setting_value,
        };
    }
}