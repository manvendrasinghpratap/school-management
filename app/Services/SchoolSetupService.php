<?php

namespace App\Services;

use App\Models\School;
use App\Models\Setting;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class SchoolSetupService
{
    public function updateSchool(
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

    public function setSetting(
        School $school,
        string $key,
        mixed $value,
        string $type = 'string'
    ): Setting {
        return Setting::updateOrCreate(
            [
                'school_id' => $school->id,
                'setting_key' => $key,
            ],
            [
                'setting_value' => is_bool($value)
                    ? ($value ? '1' : '0')
                    : (string) $value,

                'setting_type' => $type,
            ]
        );
    }

    public function updateSystemSettings(
        School $school,
        array $data
    ): void {
        DB::transaction(function () use ($school, $data) {

            $settings = [
                'timezone' => [
                    'value' => $data['timezone'],
                    'type' => 'string',
                ],

                'date_format' => [
                    'value' => $data['date_format'],
                    'type' => 'string',
                ],

                'currency' => [
                    'value' => $data['currency'],
                    'type' => 'string',
                ],

                'language' => [
                    'value' => $data['language'],
                    'type' => 'string',
                ],

                'attendance_enabled' => [
                    'value' => !empty(
                        $data['attendance_enabled']
                    ),
                    'type' => 'boolean',
                ],

                'grading_enabled' => [
                    'value' => !empty(
                        $data['grading_enabled']
                    ),
                    'type' => 'boolean',
                ],
            ];

            foreach ($settings as $key => $config) {
                $this->setSetting(
                    $school,
                    $key,
                    $config['value'],
                    $config['type']
                );
            }
        });
    }
}