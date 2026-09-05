<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Routes extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_id',
        'vehicle_id',
    ];

    public function school() { return $this->belongsTo(Schools::class, 'school_id'); }
    public function vehicle() { return $this->belongsTo(Vehicles::class, 'vehicle_id'); }
}
