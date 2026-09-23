<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Visitors extends Model
{
    use HasFactory;
    protected $table = 'visitors';
    protected $fillable = ['school_id','visitor_name','phone','purpose','person_to_visit','check_in','check_out','identification_type','identification_number','created_by'];
    protected $casts = ['check_in'=>'datetime','check_out'=>'datetime'];
    public function school(): BelongsTo { return $this->belongsTo(School::class); }
    public function createdBy(): BelongsTo { return $this->belongsTo(User::class, 'created_by'); }
}
