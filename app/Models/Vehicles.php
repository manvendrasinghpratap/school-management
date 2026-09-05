<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicles extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_id',
    ];

    public function school() { return $this->belongsTo(Schools::class, 'school_id'); }
}
