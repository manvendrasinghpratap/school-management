<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TransportDriver extends Model
{
    use SoftDeletes;
    protected $table = 'transport_drivers';
    protected $fillable = ['school_id','staff_id','name','license_number','license_expiry','phone','address','status'];
    protected $casts = ['id'=>'integer','school_id'=>'integer','staff_id'=>'integer','license_expiry'=>'date','created_at'=>'datetime','updated_at'=>'datetime','deleted_at'=>'datetime'];
    public function school(){return $this->belongsTo(School::class,'school_id');}
    public function staff(){return $this->belongsTo(Staff::class,'staff_id');}
    public function routes(){return $this->hasMany(TransportRoute::class,'driver_id');}
    public function scopeForSchool($q,int $schoolId){return $q->where($this->getTable().'.school_id',$schoolId);}
}
