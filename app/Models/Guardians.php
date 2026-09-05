<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Guardians extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_id',
        'user_id',
    ];

    public function school() { return $this->belongsTo(Schools::class, 'school_id'); }
    public function user() { return $this->belongsTo(Users::class, 'user_id'); }
}
