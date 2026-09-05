<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StaffDocuments extends Model
{
    use HasFactory;

    protected $fillable = [
        'staff_id',
        'uploaded_by',
    ];

    public function staff() { return $this->belongsTo(Staff::class, 'staff_id'); }
}
