<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TransportStop extends Model
{
    use SoftDeletes;
    protected $table = 'transport_stops';
    protected $fillable = ['school_id','route_id','name','sequence_no','pickup_time','dropoff_time','address','monthly_fee'];
    protected $casts = ['id'=>'integer','school_id'=>'integer','route_id'=>'integer','sequence_no'=>'integer','monthly_fee'=>'decimal:2','pickup_time'=>'string','dropoff_time'=>'string','created_at'=>'datetime','updated_at'=>'datetime','deleted_at'=>'datetime'];
    public function school(){return $this->belongsTo(School::class,'school_id');}
    public function route(){return $this->belongsTo(TransportRoute::class,'route_id');}
    public function scopeForSchool($q,int $schoolId){return $q->where($this->getTable().'.school_id',$schoolId);}
}
