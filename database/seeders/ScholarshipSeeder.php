<?php

namespace Database\Seeders;

use App\Models\School;
use App\Models\Scholarships;
use Illuminate\Database\Seeder;

class ScholarshipSeeder extends Seeder
{
    /**
     * Seed default scholarship definitions for every existing school.
     *
     * Idempotent: matching records are updated instead of duplicated.
     */
    public function run(): void
    {
        $schools = School::query()->get(['id']);

        if ($schools->isEmpty()) {
            $this->command?->warn(
                'ScholarshipSeeder skipped: no schools found.'
            );

            return;
        }

        $scholarships = [
            [
                'name' => 'Merit Scholarship',
                'type' => 'percentage',
                'value' => 25.00,
                'description' => 'Merit-based fee concession.',
                'is_active' => true,
            ],
            [
                'name' => 'Special Concession',
                'type' => 'fixed',
                'value' => 500.00,
                'description' => 'Fixed fee concession for approved cases.',
                'is_active' => true,
            ],
        ];

        foreach ($schools as $school) {
            foreach ($scholarships as $data) {
                Scholarships::withTrashed()->updateOrCreate(
                    [
                        'school_id' => $school->id,
                        'name' => $data['name'],
                    ],
                    [
                        'type' => $data['type'],
                        'value' => $data['value'],
                        'description' => $data['description'],
                        'is_active' => $data['is_active'],
                        'deleted_at' => null,
                    ]
                );
            }
        }

        $this->command?->info(
            'ScholarshipSeeder: default scholarships seeded successfully.'
        );
    }
}
