<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Students extends Model
{
    use HasFactory;

    protected $fillable = [
        'created_by',
        'school_id',
        'updated_by',
        'user_id',
    ];

    public function school() { return $this->belongsTo(Schools::class, 'school_id'); }
    public function user() { return $this->belongsTo(Users::class, 'user_id'); }
}
