<?php

namespace App\Services;

use App\Models\Guardian;
use Illuminate\Support\Facades\DB;

class GuardianService
{
    public function create(array $data): Guardian
    {
        return DB::transaction(function () use ($data) {
            $schoolId = $this->schoolId();

            $data['school_id'] = $schoolId;

            return Guardian::create($data)->fresh();
        });
    }

    public function update(
        Guardian $guardian,
        array $data
    ): Guardian {
        return DB::transaction(function () use (
            $guardian,
            $data
        ) {
            $this->ensureSameSchool($guardian);

            // Never allow the school to be changed through update data.
            unset($data['school_id']);

            $guardian->update($data);

            return $guardian->fresh();
        });
    }

    public function delete(Guardian $guardian): void
    {
        DB::transaction(function () use ($guardian) {
            $this->ensureSameSchool($guardian);

            $guardian->delete();
        });
    }

    protected function schoolId(): int
    {
        $user = auth()->user();

        abort_unless(
            $user,
            403,
            'You must be authenticated.'
        );

        abort_unless(
            $user->school_id,
            403,
            'No school is assigned to the current user.'
        );

        return (int) $user->school_id;
    }

    protected function ensureSameSchool(
        Guardian $guardian
    ): void {
        $schoolId = $this->schoolId();

        abort_unless(
            (int) $guardian->school_id === $schoolId,
            403,
            'You are not authorized to access this guardian.'
        );
    }
}