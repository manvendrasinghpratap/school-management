<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Spatie\Permission\Traits\HasRoles;

#[Fillable([
    'school_id',
    'user_type_id',
    'designation_id',
    'name',
    'email',
    'username',
    'email_verified_at',
    'avatar',
    'is_active',
    'is_staff',
    'password',
    'status',
    'is_deleted',
    'timezone',
    'created_by',
])]
#[Hidden([
    'password',
    'remember_token',
])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'school_id',
        'user_type_id',
        'designation_id',
        'name',
        'email',
        'username',
        'email_verified_at',
        'avatar',
        'is_active',
        'is_staff',
        'password',
        'status',
        'is_deleted',
        'timezone',
        'created_by',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
            'is_staff' => 'boolean',
            'is_deleted' => 'boolean',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    public function staff(): HasOne
    {
        return $this->hasOne(Staff::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

    public function scopeActive($query)
    {
        return $query->where('is_active', true)
                     ->where('is_deleted', false);
    }

    public function scopeNotDeleted($query)
    {
        return $query->where('is_deleted', false);
    }
}