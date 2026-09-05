<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RouteStudent extends Model
{
    use HasFactory;

    protected $fillable = [
        'route_id',
        'student_id',
    ];

    public function route() { return $this->belongsTo(Route::class, 'route_id'); }
    public function student() { return $this->belongsTo(Student::class, 'student_id'); }
}
