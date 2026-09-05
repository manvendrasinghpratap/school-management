<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sessions extends Model
{
    use HasFactory;

    protected $table = 'sessions';
    public $timestamps = false;

    protected $fillable = [
        'last_activity',
        'user_id',
    ];

    public function user() { return $this->belongsTo(Users::class, 'user_id'); }
}
