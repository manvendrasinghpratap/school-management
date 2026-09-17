<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class IdCardTemplate extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'school_id',
        'created_by',
        'name',
        'card_type',
        'front_html',
        'back_html',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function cards()
    {
        return $this->hasMany(
            IdCard::class,
            'template_id'
        );
    }
}