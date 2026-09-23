<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Designation extends Model
{
    use HasFactory;

    protected $table = 'designations';

    protected $fillable = ['account_id', 'name', 'status', 'is_deleted'];

    protected $casts = ['status' => 'boolean', 'is_deleted' => 'boolean'];

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class, 'account_id');
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'designation_id');
    }

    public function scopeForSchool($query, int $schoolId)
    {
        return $query->where('account_id', $schoolId)->where('is_deleted', false);
    }
}
