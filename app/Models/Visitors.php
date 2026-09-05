<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Visitors extends Model
{
    use HasFactory;

    protected $fillable = [
        'host_user_id',
        'school_id',
    ];

    public function host_user() { return $this->belongsTo(Users::class, 'host_user_id'); }
    public function school() { return $this->belongsTo(Schools::class, 'school_id'); }
}
