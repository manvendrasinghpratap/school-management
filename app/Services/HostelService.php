<?php

namespace App\Services;

use App\Models\Hostel;
use App\Models\HostelAllocation;
use App\Models\HostelBed;
use App\Models\HostelRoom;
use App\Models\Student;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class HostelService
{
    public function __construct(
        private AcademicHierarchyService $academicHierarchy
    ) {
    }

    private function schoolId(): int
    {
        $schoolId = Auth::user()?->school_id;

        abort_unless(
            $schoolId,
            403,
            'No school is assigned to the current user.'
        );

        return (int) $schoolId;
    }

    public function saveHostel(array $data, ?Hostel $hostel = null): Hostel
    {
        $schoolId = $this->schoolId();

        return DB::transaction(function () use ($data, $hostel, $schoolId) {
            if ($hostel && (int) $hostel->school_id !== $schoolId) {
                abort(404);
            }

            $data['school_id'] = $schoolId;
            $data['created_by'] = $hostel?->created_by ?? Auth::id();

            return $hostel
                ? $this->fillSave($hostel, $data)
                : Hostel::create($data);
        });
    }

    public function saveRoom(array $data, ?HostelRoom $room = null): HostelRoom
    {
        $schoolId = $this->schoolId();

        return DB::transaction(function () use ($data, $room, $schoolId) {
            $hostel = Hostel::query()
                ->where('id', $data['hostel_id'])
                ->where('school_id', $schoolId)
                ->where('status', 'active')
                ->firstOrFail();

            if ($room && (int) $room->school_id !== $schoolId) {
                abort(404);
            }

            $duplicate = HostelRoom::query()
                ->where('hostel_id', $hostel->id)
                ->where('room_number', $data['room_number'])
                ->when($room, fn ($q) => $q->where('id', '!=', $room->id))
                ->exists();

            if ($duplicate) {
                throw ValidationException::withMessages([
                    'room_number' => 'This room number already exists in the selected hostel.',
                ]);
            }

            $data['school_id'] = $schoolId;
            $data['hostel_id'] = $hostel->id;

            return $room
                ? $this->fillSave($room, $data)
                : HostelRoom::create($data);
        });
    }

    public function saveBed(array $data, ?HostelBed $bed = null): HostelBed
    {
        $schoolId = $this->schoolId();

        return DB::transaction(function () use ($data, $bed, $schoolId) {
            $room = HostelRoom::query()
                ->where('id', $data['room_id'])
                ->where('school_id', $schoolId)
                ->firstOrFail();

            if ($bed && (int) $bed->school_id !== $schoolId) {
                abort(404);
            }

            $duplicate = HostelBed::query()
                ->where('room_id', $room->id)
                ->where('bed_number', $data['bed_number'])
                ->when($bed, fn ($q) => $q->where('id', '!=', $bed->id))
                ->exists();

            if ($duplicate) {
                throw ValidationException::withMessages([
                    'bed_number' => 'This bed number already exists in the selected room.',
                ]);
            }

            if (! $bed) {
                $bedCount = HostelBed::query()
                    ->where('room_id', $room->id)
                    ->whereNull('deleted_at')
                    ->count();

                if ($bedCount >= (int) $room->capacity) {
                    throw ValidationException::withMessages([
                        'bed_number' => 'The room already has the maximum number of beds allowed by its capacity.',
                    ]);
                }
            }

            $data['school_id'] = $schoolId;
            $data['room_id'] = $room->id;

            return $bed
                ? $this->fillSave($bed, $data)
                : HostelBed::create($data);
        });
    }

    public function allocate(array $data): HostelAllocation
    {
        $schoolId = $this->schoolId();

        return DB::transaction(function () use ($data, $schoolId) {
            $this->academicHierarchy->validateStudentHierarchy(
                (int) $data['academic_year_id'],
                (int) $data['class_id'],
                (int) $data['section_id'],
                (int) $data['student_id']
            );

            $hostel = Hostel::query()
                ->where('id', $data['hostel_id'])
                ->where('school_id', $schoolId)
                ->where('status', 'active')
                ->firstOrFail();

            $room = HostelRoom::query()
                ->where('id', $data['room_id'])
                ->where('hostel_id', $hostel->id)
                ->where('school_id', $schoolId)
                ->whereIn('status', ['available', 'full'])
                ->firstOrFail();

            $bed = null;

            if (! empty($data['bed_id'])) {
                $bed = HostelBed::query()
                    ->where('id', $data['bed_id'])
                    ->where('room_id', $room->id)
                    ->where('school_id', $schoolId)
                    ->whereNull('deleted_at')
                    ->firstOrFail();

                if ($bed->status !== 'available') {
                    throw ValidationException::withMessages([
                        'bed_id' => 'The selected bed is not available.',
                    ]);
                }
            }

            $active = HostelAllocation::query()
                ->where('school_id', $schoolId)
                ->where('student_id', $data['student_id'])
                ->whereIn('status', ['allocated', 'checked_in'])
                ->exists();

            if ($active) {
                throw ValidationException::withMessages([
                    'student_id' => 'This student already has an active hostel allocation.',
                ]);
            }

            if (! $bed) {
                $roomOccupied = HostelAllocation::query()
                    ->where('school_id', $schoolId)
                    ->where('room_id', $room->id)
                    ->whereIn('status', ['allocated', 'checked_in'])
                    ->count();

                if ($roomOccupied >= (int) $room->capacity) {
                    throw ValidationException::withMessages([
                        'room_id' => 'The selected room has reached its capacity.',
                    ]);
                }
            }

            $allocation = HostelAllocation::create([
                'school_id' => $schoolId,
                'hostel_id' => $hostel->id,
                'room_id' => $room->id,
                'bed_id' => $bed?->id,
                'student_id' => $data['student_id'],
                'academic_year_id' => $data['academic_year_id'],
                'start_date' => $data['start_date'],
                'end_date' => $data['end_date'] ?? null,
                'monthly_fee' => $data['monthly_fee'] ?? $room->monthly_fee,
                'status' => $data['status'] ?? 'allocated',
                'notes' => $data['notes'] ?? null,
                'created_by' => Auth::id(),
            ]);

            if ($bed) {
                $bed->update(['status' => 'occupied']);
            }

            $this->refreshRoomStatus($room);

            return $allocation->fresh([
                'hostel',
                'room',
                'bed',
                'student',
                'academicYear',
            ]);
        });
    }

    public function checkout(
        HostelAllocation $allocation,
        array $data = []
    ): HostelAllocation {
        $schoolId = $this->schoolId();

        if ((int) $allocation->school_id !== $schoolId) {
            abort(404);
        }

        return DB::transaction(function () use ($allocation, $data) {
            $allocation->update([
                'end_date' => $data['end_date'] ?? now()->toDateString(),
                'status' => 'checked_out',
                'notes' => $data['notes'] ?? $allocation->notes,
            ]);

            if ($allocation->bed_id) {
                HostelBed::query()
                    ->where('id', $allocation->bed_id)
                    ->where('school_id', $this->schoolId())
                    ->update(['status' => 'available']);
            }

            if ($allocation->room_id) {
                $room = HostelRoom::query()
                    ->where('id', $allocation->room_id)
                    ->where('school_id', $this->schoolId())
                    ->first();

                if ($room) {
                    $this->refreshRoomStatus($room);
                }
            }

            return $allocation->fresh([
                'hostel',
                'room',
                'bed',
                'student',
                'academicYear',
            ]);
        });
    }

    public function delete(Model $model): void
    {
        if ((int) $model->school_id !== $this->schoolId()) {
            abort(404);
        }

        $model->delete();
    }

    private function refreshRoomStatus(HostelRoom $room): void
    {
        $occupied = HostelAllocation::query()
            ->where('school_id', $room->school_id)
            ->where('room_id', $room->id)
            ->whereIn('status', ['allocated', 'checked_in'])
            ->count();

        if ($room->status === 'maintenance' || $room->status === 'inactive') {
            return;
        }

        $room->update([
            'status' => $occupied >= (int) $room->capacity
                ? 'full'
                : 'available',
        ]);
    }

    private function fillSave(Model $model, array $data): Model
    {
        $model->fill($data);
        $model->save();

        return $model;
    }
}
