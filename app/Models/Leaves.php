<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Leaves extends Model
{
    use HasFactory;

    protected $fillable = [
        'approved_by',
        'staff_id',
    ];

    public function staff() { return $this->belongsTo(Staff::class, 'staff_id'); }
}
