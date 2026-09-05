<?php

namespace App\Services;

use App\Models\School;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class SchoolService
{
    /**
     * Create a new school.
     */
    public function create(
        array $data,
        ?UploadedFile $logo = null
    ): School {
        return DB::transaction(function () use ($data, $logo) {

            if ($logo) {
                $data['logo'] = $logo->store(
                    'schools/logos',
                    'public'
                );
            }

            $school = School::create($data);

            /*
             * If the current user does not yet belong
             * to a school, assign the newly created school.
             */
            $user = auth()->user();

            if ($user && !$user->school_id) {
                $user->school_id = $school->id;
                $user->save();
            }

            return $school->fresh();
        });
    }

    /**
     * Update an existing school.
     */
    public function update(
        School $school,
        array $data,
        ?UploadedFile $logo = null
    ): School {
        return DB::transaction(function () use (
            $school,
            $data,
            $logo
        ) {

            if ($logo) {
                if ($school->logo) {
                    Storage::disk('public')->delete(
                        $school->logo
                    );
                }

                $data['logo'] = $logo->store(
                    'schools/logos',
                    'public'
                );
            }

            $school->update($data);

            return $school->fresh();
        });
    }

    /**
     * Delete a school.
     */
    public function delete(School $school): void
    {
        DB::transaction(function () use ($school) {

            if ($school->logo) {
                Storage::disk('public')->delete(
                    $school->logo
                );
            }

            $school->delete();
        });
    }
}