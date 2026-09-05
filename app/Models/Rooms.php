<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rooms extends Model
{
    use HasFactory;

    protected $fillable = [
        'hostel_id',
    ];

    public function hostel() { return $this->belongsTo(Hostels::class, 'hostel_id'); }
}
