<?php

namespace App\Services;

use App\Models\RouteStudent;
use App\Models\TransportRoute;
use App\Models\TransportStop;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TransportService
{
    public function __construct(private Wave2SchoolScope $scope) {}

    public function assignStudent(array $data): RouteStudent
    {
        $schoolId = $this->scope->schoolId();

        $route = TransportRoute::where('school_id',$schoolId)->findOrFail($data['route_id']);
        if (!empty($data['stop_id'])) {
            TransportStop::where('school_id',$schoolId)->where('route_id',$route->id)->findOrFail($data['stop_id']);
        }

        return DB::transaction(function () use ($data, $schoolId) {
            return RouteStudent::create([
                'school_id'=>$schoolId,
                'route_id'=>$data['route_id'],
                'stop_id'=>$data['stop_id'] ?? null,
                'student_id'=>$data['student_id'],
                'academic_year_id'=>$data['academic_year_id'] ?? null,
                'start_date'=>$data['start_date'],
                'end_date'=>$data['end_date'] ?? null,
                'monthly_fee'=>$data['monthly_fee'] ?? 0,
                'status'=>'active',
                'notes'=>$data['notes'] ?? null,
                'created_by'=>Auth::id(),
            ]);
        });
    }
}
