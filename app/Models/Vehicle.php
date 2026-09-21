<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vehicle extends Model
{
    use SoftDeletes;
    protected $table = 'vehicles';
    protected $fillable = ['school_id','vehicle_number','registration_number','vehicle_type','capacity','driver_name','driver_phone','status'];
    protected $casts = ['id'=>'integer','school_id'=>'integer','capacity'=>'integer','created_at'=>'datetime','updated_at'=>'datetime','deleted_at'=>'datetime'];
    public function school(){return $this->belongsTo(School::class,'school_id');}
    public function routes(){return $this->hasMany(TransportRoute::class,'vehicle_id');}
    public function scopeForSchool($q,int $schoolId){return $q->where($this->getTable().'.school_id',$schoolId);}
}
