<?php

namespace App\Services;

use App\Models\Guardian;
use App\Models\Student;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class StudentService
{
    public function create(array $data, ?UploadedFile $photo = null): Student
    {
        return DB::transaction(function () use ($data, $photo) {
            $userId = auth()->id();
            $schoolId = $this->schoolId();

            $guardians = $data['guardians'] ?? [];
            unset($data['guardians']);

            $guardianData = $this->validateGuardianData($guardians, $schoolId);

            $data['school_id'] = $schoolId;
            $data['created_by'] = $userId;
            $data['updated_by'] = $userId;
            $data['status'] = $data['status'] ?? 'active';

            if ($photo) {
                $data['photo'] = $photo->store('students/photos', 'public');
            }

            $student = Student::create($data);

            $this->syncGuardians($student, $guardianData);

            return $student->fresh(['guardians']);
        });
    }

    public function update(
        Student $student,
        array $data,
        ?UploadedFile $photo = null
    ): Student {
        return DB::transaction(function () use ($student, $data, $photo) {
            $this->ensureSameSchool($student);

            $schoolId = $this->schoolId();

            $guardians = $data['guardians'] ?? [];
            unset($data['guardians']);

            unset($data['school_id']);

            $guardianData = $this->validateGuardianData(
                $guardians,
                $schoolId
            );

            $data['updated_by'] = auth()->id();

            if ($photo) {
                if ($student->photo) {
                    Storage::disk('public')->delete($student->photo);
                }

                $data['photo'] = $photo->store(
                    'students/photos',
                    'public'
                );
            }

            $student->update($data);

            $this->syncGuardians($student, $guardianData);

            return $student->fresh(['guardians']);
        });
    }

    public function delete(Student $student): void
    {
        DB::transaction(function () use ($student) {
            $this->ensureSameSchool($student);

            if ($student->photo) {
                Storage::disk('public')->delete($student->photo);
            }

            $student->delete();
        });
    }

    /**
     * Validate guardian selections and prepare pivot data.
     */
    protected function validateGuardianData(
        array $guardians,
        int $schoolId
    ): array {
        if (empty($guardians)) {
            return [];
        }

        $guardianIds = [];

        foreach ($guardians as $guardian) {
            if (!is_array($guardian)) {
                abort(422, 'Invalid guardian data.');
            }

            if (empty($guardian['id'])) {
                abort(422, 'A guardian must be selected.');
            }

            $guardianIds[] = (int) $guardian['id'];
        }

        $guardianIds = array_values(array_unique($guardianIds));

        $validGuardianIds = Guardian::query()
            ->where('school_id', $schoolId)
            ->whereIn('id', $guardianIds)
            ->pluck('id')
            ->map(fn ($id) => (int) $id)
            ->all();

        if (count($validGuardianIds) !== count($guardianIds)) {
            abort(
                403,
                'One or more selected guardians do not belong to your school.'
            );
        }

        $result = [];

        foreach ($guardians as $guardian) {
            $guardianId = (int) $guardian['id'];

            $result[$guardianId] = [
                'relationship' => trim($guardian['relationship'] ?? ''),
                'is_primary' => !empty($guardian['is_primary']),
                'is_emergency_contact' => !empty(
                    $guardian['is_emergency_contact']
                ),
            ];
        }

        foreach ($result as $guardianId => $pivot) {
            if ($pivot['relationship'] === '') {
                abort(
                    422,
                    'The relationship is required for every selected guardian.'
                );
            }
        }

        return $result;
    }

    /**
     * Synchronize student guardians with pivot information.
     */
    protected function syncGuardians(
        Student $student,
        array $guardianData
    ): void {
        $student->guardians()->sync($guardianData);
    }

    protected function schoolId(): int
    {
        $user = auth()->user();

        if (!$user || !$user->school_id) {
            abort(
                403,
                'No school is assigned to the current user.'
            );
        }

        return (int) $user->school_id;
    }

    protected function ensureSameSchool(Student $student): void
    {
        $schoolId = $this->schoolId();

        if ((int) $student->school_id !== $schoolId) {
            abort(
                403,
                'You are not authorized to access this student.'
            );
        }
    }
}