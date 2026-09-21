<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RouteStudent extends Model
{
    use SoftDeletes;
    protected $table = 'route_students';
    protected $fillable = ['school_id','route_id','student_id','pickup_point','dropoff_point','start_date','end_date','status'];
    protected $casts = ['id'=>'integer','school_id'=>'integer','route_id'=>'integer','student_id'=>'integer','start_date'=>'date','end_date'=>'date','created_at'=>'datetime','updated_at'=>'datetime','deleted_at'=>'datetime'];
    public function school(){return $this->belongsTo(School::class,'school_id');}
    public function route(){return $this->belongsTo(TransportRoute::class,'route_id');}
    public function student(){return $this->belongsTo(Student::class,'student_id');}
    public function fees(){return $this->hasMany(TransportFee::class,'route_student_id');}
    public function scopeForSchool($q,int $schoolId){return $q->where($this->getTable().'.school_id',$schoolId);}
}
