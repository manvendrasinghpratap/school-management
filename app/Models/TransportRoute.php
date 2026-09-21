<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TransportRoute extends Model
{
    use SoftDeletes;
    protected $table = 'transport_routes';
    protected $fillable = ['school_id','vehicle_id','driver_id','name','code','start_point','end_point','departure_time','arrival_time','monthly_fee','status','notes','created_by'];
    protected $casts = ['id'=>'integer','school_id'=>'integer','vehicle_id'=>'integer','driver_id'=>'integer','monthly_fee'=>'decimal:2','departure_time'=>'string','arrival_time'=>'string','created_by'=>'integer','created_at'=>'datetime','updated_at'=>'datetime','deleted_at'=>'datetime'];
    public function school(){return $this->belongsTo(School::class,'school_id');}
    public function vehicle(){return $this->belongsTo(Vehicle::class,'vehicle_id');}
    public function driver(){return $this->belongsTo(TransportDriver::class,'driver_id');}
    public function stops(){return $this->hasMany(TransportStop::class,'route_id')->orderBy('sequence_no');}
    public function assignments(){return $this->hasMany(RouteStudent::class,'route_id');}
    public function creator(){return $this->belongsTo(User::class,'created_by');}
    public function scopeForSchool($q,int $schoolId){return $q->where($this->getTable().'.school_id',$schoolId);}
}
