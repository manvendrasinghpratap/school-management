<?php

namespace App\Services;

use App\Models\HostelAllocation;
use App\Models\HostelBed;
use App\Models\HostelRoom;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class HostelService
{
    public function __construct(private Wave2SchoolScope $scope) {}

    public function allocate(array $data): HostelAllocation
    {
        $schoolId = $this->scope->schoolId();

        $room = HostelRoom::where('school_id',$schoolId)->findOrFail($data['room_id']);
        $bed = !empty($data['bed_id'])
            ? HostelBed::where('school_id',$schoolId)->where('room_id',$room->id)->findOrFail($data['bed_id'])
            : null;

        if ($bed && $bed->status !== 'available') {
            throw ValidationException::withMessages(['bed_id'=>'Selected bed is not available.']);
        }

        $active = HostelAllocation::where('school_id',$schoolId)
            ->where('student_id',$data['student_id'])
            ->whereIn('status',['allocated','checked_in'])
            ->exists();

        if ($active) {
            throw ValidationException::withMessages(['student_id'=>'This student already has an active hostel allocation.']);
        }

        return DB::transaction(function () use ($data,$schoolId,$bed) {
            $allocation = HostelAllocation::create([
                'school_id'=>$schoolId,
                'hostel_id'=>$data['hostel_id'],
                'room_id'=>$data['room_id'],
                'bed_id'=>$data['bed_id'] ?? null,
                'student_id'=>$data['student_id'],
                'academic_year_id'=>$data['academic_year_id'] ?? null,
                'start_date'=>$data['start_date'],
                'end_date'=>$data['end_date'] ?? null,
                'monthly_fee'=>$data['monthly_fee'] ?? 0,
                'status'=>$data['status'] ?? 'allocated',
                'notes'=>$data['notes'] ?? null,
                'created_by'=>Auth::id(),
            ]);

            if ($bed) {
                $bed->update(['status'=>'occupied']);
            }

            return $allocation->fresh(['hostel','room','bed','student']);
        });
    }

    public function checkout(HostelAllocation $allocation, array $data): HostelAllocation
    {
        $schoolId = $this->scope->schoolId();
        if ((int)$allocation->school_id !== $schoolId) abort(404);

        return DB::transaction(function () use ($allocation,$data) {
            $allocation->update([
                'end_date'=>$data['end_date'] ?? now()->toDateString(),
                'status'=>'checked_out',
                'notes'=>$data['notes'] ?? $allocation->notes,
            ]);

            if ($allocation->bed_id) {
                HostelBed::whereKey($allocation->bed_id)->update(['status'=>'available']);
            }

            return $allocation->fresh(['hostel','room','bed','student']);
        });
    }
}
