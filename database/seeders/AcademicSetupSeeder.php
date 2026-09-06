<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AcademicSetupSeeder extends Seeder
{
    public function run(): void
    {
        $schoolId = 1;

        /*
        |--------------------------------------------------------------------------
        | Academic Years
        |--------------------------------------------------------------------------
        */

        $academicYears = [
            [
                'name' => '2025/2026',
                'start_date' => '2025-09-01',
                'end_date' => '2026-07-31',
                'is_current' => 0,
                'is_active' => 1,
            ],
            [
                'name' => '2026/2027',
                'start_date' => '2026-09-01',
                'end_date' => '2027-07-31',
                'is_current' => 1,
                'is_active' => 1,
            ],
        ];

        $academicYearIds = [];

        foreach ($academicYears as $year) {
            $id = DB::table('academic_years')
                ->where('school_id', $schoolId)
                ->where('name', $year['name'])
                ->value('id');

            if ($id) {
                DB::table('academic_years')
                    ->where('id', $id)
                    ->update([
                        'start_date' => $year['start_date'],
                        'end_date' => $year['end_date'],
                        'is_current' => $year['is_current'],
                        'is_active' => $year['is_active'],
                        'deleted_at' => null,
                        'updated_at' => now(),
                    ]);
            } else {
                $id = DB::table('academic_years')->insertGetId([
                    'school_id' => $schoolId,
                    'name' => $year['name'],
                    'start_date' => $year['start_date'],
                    'end_date' => $year['end_date'],
                    'is_current' => $year['is_current'],
                    'is_active' => $year['is_active'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            $academicYearIds[$year['name']] = $id;
        }

        /*
        |--------------------------------------------------------------------------
        | Terms
        |--------------------------------------------------------------------------
        */

        $terms = [
            [
                'academic_year' => '2026/2027',
                'name' => 'First Term',
                'term_number' => 1,
                'start_date' => '2026-09-01',
                'end_date' => '2026-12-18',
                'is_current' => 1,
            ],
            [
                'academic_year' => '2026/2027',
                'name' => 'Second Term',
                'term_number' => 2,
                'start_date' => '2027-01-05',
                'end_date' => '2027-04-02',
                'is_current' => 0,
            ],
            [
                'academic_year' => '2026/2027',
                'name' => 'Third Term',
                'term_number' => 3,
                'start_date' => '2027-04-19',
                'end_date' => '2027-07-31',
                'is_current' => 0,
            ],

            [
                'academic_year' => '2025/2026',
                'name' => 'First Term',
                'term_number' => 1,
                'start_date' => '2025-09-01',
                'end_date' => '2025-12-19',
                'is_current' => 0,
            ],
            [
                'academic_year' => '2025/2026',
                'name' => 'Second Term',
                'term_number' => 2,
                'start_date' => '2026-01-05',
                'end_date' => '2026-04-02',
                'is_current' => 0,
            ],
            [
                'academic_year' => '2025/2026',
                'name' => 'Third Term',
                'term_number' => 3,
                'start_date' => '2026-04-20',
                'end_date' => '2026-07-31',
                'is_current' => 0,
            ],
        ];

        foreach ($terms as $term) {
            $academicYearId = $academicYearIds[$term['academic_year']];

            $existingId = DB::table('terms')
                ->where('school_id', $schoolId)
                ->where('academic_year_id', $academicYearId)
                ->where('term_number', $term['term_number'])
                ->value('id');

            $data = [
                'school_id' => $schoolId,
                'academic_year_id' => $academicYearId,
                'name' => $term['name'],
                'term_number' => $term['term_number'],
                'start_date' => $term['start_date'],
                'end_date' => $term['end_date'],
                'is_current' => $term['is_current'],
                'is_active' => 1,
                'deleted_at' => null,
                'updated_at' => now(),
            ];

            if ($existingId) {
                DB::table('terms')
                    ->where('id', $existingId)
                    ->update($data);
            } else {
                $data['created_at'] = now();

                DB::table('terms')->insert($data);
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Departments
        |--------------------------------------------------------------------------
        */

        $departments = [
            [
                'name' => 'Administration',
                'code' => 'ADMIN',
                'description' => 'School administration and management.',
            ],
            [
                'name' => 'Sciences',
                'code' => 'SCI',
                'description' => 'Science-related academic subjects.',
            ],
            [
                'name' => 'Humanities',
                'code' => 'HUM',
                'description' => 'Humanities and social science subjects.',
            ],
            [
                'name' => 'Languages',
                'code' => 'LANG',
                'description' => 'Languages and communication subjects.',
            ],
            [
                'name' => 'Mathematics',
                'code' => 'MATH',
                'description' => 'Mathematics and quantitative studies.',
            ],
            [
                'name' => 'ICT',
                'code' => 'ICT',
                'description' => 'Information and communication technology.',
            ],
        ];

        $departmentIds = [];

        foreach ($departments as $department) {
            $existingId = DB::table('departments')
                ->where('school_id', $schoolId)
                ->where('code', $department['code'])
                ->value('id');

            $data = [
                'school_id' => $schoolId,
                'name' => $department['name'],
                'code' => $department['code'],
                'description' => $department['description'],
                'is_active' => 1,
                'deleted_at' => null,
                'updated_at' => now(),
            ];

            if ($existingId) {
                DB::table('departments')
                    ->where('id', $existingId)
                    ->update($data);

                $departmentIds[$department['code']] = $existingId;
            } else {
                $data['created_at'] = now();

                $id = DB::table('departments')->insertGetId($data);

                $departmentIds[$department['code']] = $id;
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Levels
        |--------------------------------------------------------------------------
        */

        $levels = [
            [
                'name' => 'Early Years',
                'code' => 'EY',
                'description' => 'Early childhood education.',
            ],
            [
                'name' => 'Primary',
                'code' => 'PRI',
                'description' => 'Primary school level.',
            ],
            [
                'name' => 'Junior Secondary',
                'code' => 'JSS',
                'description' => 'Junior secondary school level.',
            ],
            [
                'name' => 'Senior Secondary',
                'code' => 'SSS',
                'description' => 'Senior secondary school level.',
            ],
        ];

        $levelIds = [];

        foreach ($levels as $level) {
            $existingId = DB::table('levels')
                ->where('code', $level['code'])
                ->value('id');

            $data = [
                'name' => $level['name'],
                'code' => $level['code'],
                'description' => $level['description'],
                'is_active' => 1,
                'updated_at' => now(),
            ];

            if ($existingId) {
                DB::table('levels')
                    ->where('id', $existingId)
                    ->update($data);

                $levelIds[$level['code']] = $existingId;
            } else {
                $data['created_at'] = now();

                $id = DB::table('levels')->insertGetId($data);

                $levelIds[$level['code']] = $id;
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Classes
        |--------------------------------------------------------------------------
        */

        $classes = [
            [
                'name' => 'Primary 1',
                'code' => 'PRI1',
                'level' => 'PRI',
                'department' => 'ADMIN',
                'description' => 'Primary One.',
            ],
            [
                'name' => 'Primary 2',
                'code' => 'PRI2',
                'level' => 'PRI',
                'department' => 'ADMIN',
                'description' => 'Primary Two.',
            ],
            [
                'name' => 'Primary 3',
                'code' => 'PRI3',
                'level' => 'PRI',
                'department' => 'ADMIN',
                'description' => 'Primary Three.',
            ],
            [
                'name' => 'JSS 1',
                'code' => 'JSS1',
                'level' => 'JSS',
                'department' => 'SCI',
                'description' => 'Junior Secondary School One.',
            ],
            [
                'name' => 'JSS 2',
                'code' => 'JSS2',
                'level' => 'JSS',
                'department' => 'SCI',
                'description' => 'Junior Secondary School Two.',
            ],
            [
                'name' => 'JSS 3',
                'code' => 'JSS3',
                'level' => 'JSS',
                'department' => 'SCI',
                'description' => 'Junior Secondary School Three.',
            ],
            [
                'name' => 'SS 1',
                'code' => 'SS1',
                'level' => 'SSS',
                'department' => 'SCI',
                'description' => 'Senior Secondary School One.',
            ],
            [
                'name' => 'SS 2',
                'code' => 'SS2',
                'level' => 'SSS',
                'department' => 'SCI',
                'description' => 'Senior Secondary School Two.',
            ],
            [
                'name' => 'SS 3',
                'code' => 'SS3',
                'level' => 'SSS',
                'department' => 'SCI',
                'description' => 'Senior Secondary School Three.',
            ],
        ];

        $classIds = [];

        foreach ($classes as $class) {
            $departmentId = $departmentIds[$class['department']];
            $levelId = $levelIds[$class['level']];

            $existingId = DB::table('classes')
                ->where('school_id', $schoolId)
                ->where('code', $class['code'])
                ->value('id');

            $data = [
                'school_id' => $schoolId,
                'department_id' => $departmentId,
                'level_id' => $levelId,
                'name' => $class['name'],
                'code' => $class['code'],
                'description' => $class['description'],
                'is_active' => 1,
                'deleted_at' => null,
                'updated_at' => now(),
            ];

            if ($existingId) {
                DB::table('classes')
                    ->where('id', $existingId)
                    ->update($data);

                $classIds[$class['code']] = $existingId;
            } else {
                $data['created_at'] = now();

                $id = DB::table('classes')->insertGetId($data);

                $classIds[$class['code']] = $id;
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Sections
        |--------------------------------------------------------------------------
        */

        $sections = [
            ['class' => 'PRI1', 'name' => 'A', 'code' => 'PRI1-A', 'capacity' => 35],
            ['class' => 'PRI1', 'name' => 'B', 'code' => 'PRI1-B', 'capacity' => 35],

            ['class' => 'PRI2', 'name' => 'A', 'code' => 'PRI2-A', 'capacity' => 35],
            ['class' => 'PRI2', 'name' => 'B', 'code' => 'PRI2-B', 'capacity' => 35],

            ['class' => 'PRI3', 'name' => 'A', 'code' => 'PRI3-A', 'capacity' => 35],
            ['class' => 'PRI3', 'name' => 'B', 'code' => 'PRI3-B', 'capacity' => 35],

            ['class' => 'JSS1', 'name' => 'A', 'code' => 'JSS1-A', 'capacity' => 40],
            ['class' => 'JSS1', 'name' => 'B', 'code' => 'JSS1-B', 'capacity' => 40],

            ['class' => 'JSS2', 'name' => 'A', 'code' => 'JSS2-A', 'capacity' => 40],
            ['class' => 'JSS2', 'name' => 'B', 'code' => 'JSS2-B', 'capacity' => 40],

            ['class' => 'JSS3', 'name' => 'A', 'code' => 'JSS3-A', 'capacity' => 40],
            ['class' => 'JSS3', 'name' => 'B', 'code' => 'JSS3-B', 'capacity' => 40],

            ['class' => 'SS1', 'name' => 'A', 'code' => 'SS1-A', 'capacity' => 40],
            ['class' => 'SS1', 'name' => 'B', 'code' => 'SS1-B', 'capacity' => 40],

            ['class' => 'SS2', 'name' => 'A', 'code' => 'SS2-A', 'capacity' => 40],
            ['class' => 'SS2', 'name' => 'B', 'code' => 'SS2-B', 'capacity' => 40],

            ['class' => 'SS3', 'name' => 'A', 'code' => 'SS3-A', 'capacity' => 40],
            ['class' => 'SS3', 'name' => 'B', 'code' => 'SS3-B', 'capacity' => 40],
        ];

        foreach ($sections as $section) {
            $classId = $classIds[$section['class']];

            $existingId = DB::table('sections')
                ->where('class_id', $classId)
                ->where('code', $section['code'])
                ->value('id');

            $data = [
                'class_id' => $classId,
                'name' => $section['name'],
                'code' => $section['code'],
                'capacity' => $section['capacity'],
                'is_active' => 1,
                'updated_at' => now(),
            ];

            if ($existingId) {
                DB::table('sections')
                    ->where('id', $existingId)
                    ->update($data);
            } else {
                $data['created_at'] = now();

                DB::table('sections')->insert($data);
            }
        }

        $this->command?->info('Academic setup reference data seeded successfully.');
    }
}