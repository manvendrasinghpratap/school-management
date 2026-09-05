<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class School extends Model
{
    protected $fillable = [
        'name',
        'code',
        'registration_number',
        'logo',
        'address',
        'phone',
        'email',
        'website',
    ];

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function settings(): HasMany
    {
        return $this->hasMany(Setting::class);
    }

    public function setting(
        string $key,
        mixed $default = null
    ): mixed {
        $setting = $this->settings()
            ->where('setting_key', $key)
            ->first();

        return $setting?->setting_value ?? $default;
    }
}