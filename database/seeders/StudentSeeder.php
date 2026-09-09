<?php

namespace Database\Seeders;

use App\Models\AcademicYears;
use App\Models\Classes;
use App\Models\Guardian;
use App\Models\Section;
use App\Models\Student;
use App\Models\StudentEnrollment;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StudentSeeder extends Seeder
{
    /**
     * Seed student-related test data.
     *
     * Creates:
     *  - Students
     *  - Guardians
     *  - Student ↔ Guardian relationships
     *  - Student enrollments
     *
     * This seeder DOES NOT create courses or course assignments.
     */
    public function run(): void
    {
        $schoolId = 1;

        /*
         * -------------------------------------------------------------
         * 1. CURRENT ACADEMIC YEAR
         * -------------------------------------------------------------
         */
        $academicYear = AcademicYears::query()
            ->where('school_id', $schoolId)
            ->where('is_current', true)
            ->where('is_active', true)
            ->first();

        if (!$academicYear) {
            $academicYear = AcademicYears::query()
                ->where('school_id', $schoolId)
                ->where('is_active', true)
                ->orderByDesc('start_date')
                ->first();
        }

        if (!$academicYear) {
            throw new \RuntimeException(
                "No academic year found for school ID {$schoolId}. " .
                "Please run AcademicSetupSeeder first."
            );
        }

        /*
         * -------------------------------------------------------------
         * 2. CURRENT TERM
         * -------------------------------------------------------------
         */
        $term = DB::table('terms')
            ->where('school_id', $schoolId)
            ->where('academic_year_id', $academicYear->id)
            ->where('is_current', true)
            ->where('is_active', true)
            ->first();

        if (!$term) {
            $term = DB::table('terms')
                ->where('school_id', $schoolId)
                ->where('academic_year_id', $academicYear->id)
                ->where('is_active', true)
                ->orderBy('term_number')
                ->first();
        }

        if (!$term) {
            throw new \RuntimeException(
                "No term found for academic year {$academicYear->id}. " .
                "Please run AcademicSetupSeeder first."
            );
        }

        /*
         * -------------------------------------------------------------
         * 3. CLASSES
         * -------------------------------------------------------------
         */
        $classes = Classes::query()
            ->where('school_id', $schoolId)
            ->where('is_active', true)
            ->get()
            ->keyBy('name');

        $requiredClasses = [
            'Primary 1',
            'Primary 2',
            'Primary 3',
            'JSS 1',
            'JSS 2',
            'JSS 3',
            'SS 1',
            'SS 2',
            'SS 3',
        ];

        foreach ($requiredClasses as $className) {
            if (!$classes->has($className)) {
                throw new \RuntimeException(
                    "Required class '{$className}' was not found."
                );
            }
        }

        /*
         * -------------------------------------------------------------
         * 4. SECTIONS
         * -------------------------------------------------------------
         */
        $sections = Section::query()
            ->whereIn('class_id', $classes->pluck('id'))
            ->where('is_active', true)
            ->get()
            ->groupBy('class_id');

        /*
         * -------------------------------------------------------------
         * 5. STUDENTS
         * -------------------------------------------------------------
         */
        $studentDefinitions = [

            [
                'number' => 'STU-2026-001',
                'admission_number' => 'ADM-2026-001',
                'first_name' => 'Shiv',
                'middle_name' => 'Prakash',
                'last_name' => 'Shukla',
                'date_of_birth' => '2001-01-30',
                'nationality' => 'India',
                'gender' => 'male',
                'phone' => '6666666666',
                'address' => 'Lko',
                'class' => 'Primary 2',
                'section' => 'A',
            ],

            [
                'number' => 'STU-2026-002',
                'admission_number' => 'ADM-2026-002',
                'first_name' => 'Aarav',
                'middle_name' => null,
                'last_name' => 'Sharma',
                'date_of_birth' => '2015-04-12',
                'nationality' => 'India',
                'gender' => 'male',
                'phone' => '9000000001',
                'address' => 'School Avenue',
                'class' => 'Primary 1',
                'section' => 'A',
            ],

            [
                'number' => 'STU-2026-003',
                'admission_number' => 'ADM-2026-003',
                'first_name' => 'Ananya',
                'middle_name' => null,
                'last_name' => 'Singh',
                'date_of_birth' => '2014-08-20',
                'nationality' => 'India',
                'gender' => 'female',
                'phone' => '9000000002',
                'address' => 'Green Street',
                'class' => 'Primary 2',
                'section' => 'B',
            ],

            [
                'number' => 'STU-2026-004',
                'admission_number' => 'ADM-2026-004',
                'first_name' => 'Rahul',
                'middle_name' => null,
                'last_name' => 'Verma',
                'date_of_birth' => '2013-02-15',
                'nationality' => 'India',
                'gender' => 'male',
                'phone' => '9000000003',
                'address' => 'Lake Road',
                'class' => 'Primary 3',
                'section' => 'A',
            ],

            [
                'number' => 'STU-2026-005',
                'admission_number' => 'ADM-2026-005',
                'first_name' => 'Priya',
                'middle_name' => null,
                'last_name' => 'Patel',
                'date_of_birth' => '2013-06-10',
                'nationality' => 'India',
                'gender' => 'female',
                'phone' => '9000000004',
                'address' => 'Market Road',
                'class' => 'Primary 3',
                'section' => 'B',
            ],

            [
                'number' => 'STU-2026-006',
                'admission_number' => 'ADM-2026-006',
                'first_name' => 'Daniel',
                'middle_name' => null,
                'last_name' => 'Okoro',
                'date_of_birth' => '2012-03-22',
                'nationality' => 'Nigeria',
                'gender' => 'male',
                'phone' => '9000000005',
                'address' => 'Main Road',
                'class' => 'JSS 1',
                'section' => 'A',
            ],

            [
                'number' => 'STU-2026-007',
                'admission_number' => 'ADM-2026-007',
                'first_name' => 'Grace',
                'middle_name' => null,
                'last_name' => 'Williams',
                'date_of_birth' => '2011-11-08',
                'nationality' => 'Nigeria',
                'gender' => 'female',
                'phone' => '9000000006',
                'address' => 'Hill Road',
                'class' => 'JSS 2',
                'section' => 'B',
            ],

            [
                'number' => 'STU-2026-008',
                'admission_number' => 'ADM-2026-008',
                'first_name' => 'Michael',
                'middle_name' => null,
                'last_name' => 'Adeyemi',
                'date_of_birth' => '2010-05-18',
                'nationality' => 'Nigeria',
                'gender' => 'male',
                'phone' => '9000000007',
                'address' => 'Central Road',
                'class' => 'JSS 3',
                'section' => 'A',
            ],

            [
                'number' => 'STU-2026-009',
                'admission_number' => 'ADM-2026-009',
                'first_name' => 'Sarah',
                'middle_name' => null,
                'last_name' => 'Brown',
                'date_of_birth' => '2009-09-25',
                'nationality' => 'Nigeria',
                'gender' => 'female',
                'phone' => '9000000008',
                'address' => 'Garden Road',
                'class' => 'SS 1',
                'section' => 'A',
            ],

            [
                'number' => 'STU-2026-010',
                'admission_number' => 'ADM-2026-010',
                'first_name' => 'David',
                'middle_name' => null,
                'last_name' => 'Johnson',
                'date_of_birth' => '2008-12-03',
                'nationality' => 'Nigeria',
                'gender' => 'male',
                'phone' => '9000000009',
                'address' => 'West Avenue',
                'class' => 'SS 2',
                'section' => 'B',
            ],
        ];

        /*
         * -------------------------------------------------------------
         * 6. GUARDIANS
         * -------------------------------------------------------------
         *
         * IMPORTANT:
         * The guardians table does NOT contain:
         *
         * - relationship
         * - status
         *
         * Relationship belongs to student_guardians.
         */
        $guardianDefinitions = [

            [
                'number' => 'GDN-2026-001',
                'first_name' => 'Suraj',
                'middle_name' => 'Bhan',
                'last_name' => 'Singh',
                'phone' => '8000000001',
                'email' => 'suraj.singh@example.test',
                'address' => 'Lko',
            ],

            [
                'number' => 'GDN-2026-002',
                'first_name' => 'Sunita',
                'middle_name' => null,
                'last_name' => 'Shukla',
                'phone' => '8000000002',
                'email' => 'sunita.shukla@example.test',
                'address' => 'Lko',
            ],

            [
                'number' => 'GDN-2026-003',
                'first_name' => 'Rajesh',
                'middle_name' => null,
                'last_name' => 'Sharma',
                'phone' => '8000000003',
                'email' => 'rajesh.sharma@example.test',
                'address' => 'School Avenue',
            ],

            [
                'number' => 'GDN-2026-004',
                'first_name' => 'Meena',
                'middle_name' => null,
                'last_name' => 'Singh',
                'phone' => '8000000004',
                'email' => 'meena.singh@example.test',
                'address' => 'Green Street',
            ],

            [
                'number' => 'GDN-2026-005',
                'first_name' => 'Vijay',
                'middle_name' => null,
                'last_name' => 'Verma',
                'phone' => '8000000005',
                'email' => 'vijay.verma@example.test',
                'address' => 'Lake Road',
            ],

            [
                'number' => 'GDN-2026-006',
                'first_name' => 'Anita',
                'middle_name' => null,
                'last_name' => 'Patel',
                'phone' => '8000000006',
                'email' => 'anita.patel@example.test',
                'address' => 'Market Road',
            ],
        ];

        /*
         * -------------------------------------------------------------
         * 7. CREATE / UPDATE GUARDIANS
         * -------------------------------------------------------------
         */
        $guardians = [];

        foreach ($guardianDefinitions as $guardianData) {

            $guardian = Guardian::updateOrCreate(
                [
                    'school_id' => $schoolId,
                    'guardian_number' => $guardianData['number'],
                ],
                [
                    'first_name' => $guardianData['first_name'],
                    'middle_name' => $guardianData['middle_name'],
                    'last_name' => $guardianData['last_name'],
                    'phone' => $guardianData['phone'],
                    'email' => $guardianData['email'],
                    'address' => $guardianData['address'],
                ]
            );

            $guardians[$guardianData['number']] = $guardian;
        }

        /*
         * -------------------------------------------------------------
         * 8. CREATE / UPDATE STUDENTS
         * -------------------------------------------------------------
         */
        $students = [];

        foreach ($studentDefinitions as $studentData) {

            $student = Student::updateOrCreate(
                [
                    'school_id' => $schoolId,
                    'student_number' => $studentData['number'],
                ],
                [
                    'admission_number' => $studentData['admission_number'],
                    'first_name' => $studentData['first_name'],
                    'middle_name' => $studentData['middle_name'],
                    'last_name' => $studentData['last_name'],
                    'date_of_birth' => $studentData['date_of_birth'],
                    'nationality' => $studentData['nationality'],
                    'gender' => $studentData['gender'],
                    'phone' => $studentData['phone'],
                    'admission_date' => now()->toDateString(),
                    'status' => 'active',
                    'address' => $studentData['address'],
                    'updated_by' => 3,
                ]
            );

            $students[$studentData['number']] = $student;
        }

        /*
         * -------------------------------------------------------------
         * 9. STUDENT ↔ GUARDIAN RELATIONSHIPS
         * -------------------------------------------------------------
         */
        $guardianMap = [

            /*
             * Shiv has two guardians.
             */
            'STU-2026-001' => [
                [
                    'guardian' => 'GDN-2026-001',
                    'relationship' => 'Stepfather',
                    'primary' => true,
                    'emergency' => true,
                ],
                [
                    'guardian' => 'GDN-2026-002',
                    'relationship' => 'Mother',
                    'primary' => false,
                    'emergency' => true,
                ],
            ],

            'STU-2026-002' => [
                [
                    'guardian' => 'GDN-2026-003',
                    'relationship' => 'Father',
                    'primary' => true,
                    'emergency' => true,
                ],
            ],

            'STU-2026-003' => [
                [
                    'guardian' => 'GDN-2026-004',
                    'relationship' => 'Mother',
                    'primary' => true,
                    'emergency' => true,
                ],
            ],

            'STU-2026-004' => [
                [
                    'guardian' => 'GDN-2026-005',
                    'relationship' => 'Father',
                    'primary' => true,
                    'emergency' => true,
                ],
            ],

            'STU-2026-005' => [
                [
                    'guardian' => 'GDN-2026-006',
                    'relationship' => 'Mother',
                    'primary' => true,
                    'emergency' => true,
                ],
            ],

            'STU-2026-006' => [
                [
                    'guardian' => 'GDN-2026-001',
                    'relationship' => 'Father',
                    'primary' => true,
                    'emergency' => true,
                ],
            ],

            'STU-2026-007' => [
                [
                    'guardian' => 'GDN-2026-002',
                    'relationship' => 'Mother',
                    'primary' => true,
                    'emergency' => true,
                ],
            ],

            'STU-2026-008' => [
                [
                    'guardian' => 'GDN-2026-003',
                    'relationship' => 'Father',
                    'primary' => true,
                    'emergency' => true,
                ],
            ],

            'STU-2026-009' => [
                [
                    'guardian' => 'GDN-2026-004',
                    'relationship' => 'Mother',
                    'primary' => true,
                    'emergency' => true,
                ],
            ],

            'STU-2026-010' => [
                [
                    'guardian' => 'GDN-2026-005',
                    'relationship' => 'Father',
                    'primary' => true,
                    'emergency' => true,
                ],
            ],
        ];

        foreach ($guardianMap as $studentNumber => $relationships) {

            $student = $students[$studentNumber];

            /*
             * Remove existing relationships for this seeded student.
             *
             * This makes the seeder repeatable.
             */
            DB::table('student_guardians')
                ->where('student_id', $student->id)
                ->delete();

            foreach ($relationships as $relationshipData) {

                $guardian = $guardians[$relationshipData['guardian']];

                DB::table('student_guardians')->insert([
                    'student_id' => $student->id,
                    'guardian_id' => $guardian->id,

                    /*
                     * REQUIRED FIELD.
                     */
                    'relationship' => $relationshipData['relationship'],

                    'is_primary' => $relationshipData['primary']
                        ? 1
                        : 0,

                    'is_emergency_contact' => $relationshipData['emergency']
                        ? 1
                        : 0,

                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        /*
         * -------------------------------------------------------------
         * 10. STUDENT ENROLLMENTS
         * -------------------------------------------------------------
         */
        foreach ($studentDefinitions as $studentData) {

            $student = $students[$studentData['number']];

            $class = $classes[$studentData['class']];

            /*
             * Find section belonging to the selected class.
             */
            $section = $sections
                ->get($class->id, collect())
                ->firstWhere('name', $studentData['section']);

            if (!$section) {
                throw new \RuntimeException(
                    "Section {$studentData['section']} was not found " .
                    "for {$class->name}."
                );
            }

            /*
             * Find an existing active enrollment.
             */
            $enrollment = StudentEnrollment::query()
                ->where('school_id', $schoolId)
                ->where('student_id', $student->id)
                ->where('academic_year_id', $academicYear->id)
                ->where('class_id', $class->id)
                ->where('status', 'active')
                ->first();

            if (!$enrollment) {

                StudentEnrollment::create([
                    'school_id' => $schoolId,
                    'student_id' => $student->id,
                    'academic_year_id' => $academicYear->id,
                    'term_id' => $term->id,
                    'class_id' => $class->id,
                    'section_id' => $section->id,

                    'enrollment_number' =>
                        $this->generateEnrollmentNumber(
                            $schoolId,
                            $student->id
                        ),

                    'enrollment_date' => now()->toDateString(),
                    'status' => 'active',
                    'notes' => 'Created by StudentSeeder.',
                    'created_by' => 3,
                    'updated_by' => 3,
                ]);

            } else {

                /*
                 * Update the existing seeded enrollment.
                 */
                $enrollment->update([
                    'term_id' => $term->id,
                    'section_id' => $section->id,
                    'updated_by' => 3,
                ]);
            }
        }

        /*
         * -------------------------------------------------------------
         * 11. SUMMARY
         * -------------------------------------------------------------
         */
        $this->command?->newLine();

        $this->command?->info(
            'StudentSeeder completed successfully.'
        );

        $this->command?->info(
            'Students seeded: ' . count($students)
        );

        $this->command?->info(
            'Guardians seeded: ' . count($guardians)
        );

        $relationshipCount = DB::table('student_guardians')
            ->whereIn(
                'student_id',
                collect($students)->pluck('id')
            )
            ->count();

        $this->command?->info(
            'Student-Guardian relationships: ' . $relationshipCount
        );

        $enrollmentCount = StudentEnrollment::query()
            ->where('school_id', $schoolId)
            ->whereIn(
                'student_id',
                collect($students)->pluck('id')
            )
            ->where('academic_year_id', $academicYear->id)
            ->where('status', 'active')
            ->count();

        $this->command?->info(
            'Active enrollments: ' . $enrollmentCount
        );

        $this->command?->info(
            'Academic Year: ' . $academicYear->name
        );

        $this->command?->info(
            'Current Term ID: ' . $term->id
        );
    }

    /**
     * Generate a unique enrollment number.
     */
    private function generateEnrollmentNumber(
        int $schoolId,
        int $studentId
    ): string {
        do {

            $number = sprintf(
                'ENR-%d-%d-%s-%d',
                $schoolId,
                $studentId,
                now()->format('YmdHis'),
                random_int(100, 999)
            );

        } while (
            StudentEnrollment::withTrashed()
                ->where('enrollment_number', $number)
                ->exists()
        );

        return $number;
    }
}