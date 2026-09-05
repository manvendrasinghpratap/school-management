<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Complaints extends Model
{
    use HasFactory;

    protected $fillable = [
        'assigned_to',
        'school_id',
        'submitted_by',
    ];

    public function school() { return $this->belongsTo(Schools::class, 'school_id'); }
}
