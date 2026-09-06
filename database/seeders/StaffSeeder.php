<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class StaffSeeder extends Seeder
{
    public function run(): void
    {
        $schoolId = 1;

        /*
        |--------------------------------------------------------------------------
        | Staff Reference Data
        |--------------------------------------------------------------------------
        |
        | Existing users are reused where appropriate.
        | Additional staff users are created when necessary.
        |
        */

        $staffMembers = [
            [
                'user_id' => 4,
                'staff_number' => 'STF-2026-001',
                'first_name' => 'John',
                'middle_name' => null,
                'last_name' => 'Teacher',
                'department_code' => 'SCI',
                'gender' => 'male',
                'date_of_birth' => '1988-04-15',
                'nationality' => 'Nigerian',
                'phone' => '+2348000000001',
                'staff_type' => 'Teaching',
                'employment_date' => '2022-09-01',
                'status' => 'active',
                'specialization' => 'Physics',
                'qualification' => 'B.Sc. Physics, PGDE',
                'role' => 'Teacher',
            ],

            [
                'user_id' => null,
                'staff_number' => 'STF-2026-002',
                'first_name' => 'Sarah',
                'middle_name' => 'Grace',
                'last_name' => 'Williams',
                'department_code' => 'MATH',
                'gender' => 'female',
                'date_of_birth' => '1990-07-22',
                'nationality' => 'Nigerian',
                'phone' => '+2348000000002',
                'staff_type' => 'Teaching',
                'employment_date' => '2023-01-09',
                'status' => 'active',
                'specialization' => 'Mathematics',
                'qualification' => 'B.Sc. Mathematics, M.Ed.',
                'email' => 'sarah.williams@greenfieldschool.test',
                'username' => 'sarah.williams',
                'role' => 'Teacher',
            ],

            [
                'user_id' => null,
                'staff_number' => 'STF-2026-003',
                'first_name' => 'Michael',
                'middle_name' => 'David',
                'last_name' => 'Okoro',
                'department_code' => 'ICT',
                'gender' => 'male',
                'date_of_birth' => '1987-11-10',
                'nationality' => 'Nigerian',
                'phone' => '+2348000000003',
                'staff_type' => 'Teaching',
                'employment_date' => '2021-09-06',
                'status' => 'active',
                'specialization' => 'Computer Science',
                'qualification' => 'B.Sc. Computer Science, M.Sc. IT',
                'email' => 'michael.okoro@greenfieldschool.test',
                'username' => 'michael.okoro',
                'role' => 'Teacher',
            ],

            [
                'user_id' => null,
                'staff_number' => 'STF-2026-004',
                'first_name' => 'Elizabeth',
                'middle_name' => 'Anne',
                'last_name' => 'Brown',
                'department_code' => 'LANG',
                'gender' => 'female',
                'date_of_birth' => '1992-02-18',
                'nationality' => 'Nigerian',
                'phone' => '+2348000000004',
                'staff_type' => 'Teaching',
                'employment_date' => '2024-01-08',
                'status' => 'active',
                'specialization' => 'English Language',
                'qualification' => 'B.A. English, PGDE',
                'email' => 'elizabeth.brown@greenfieldschool.test',
                'username' => 'elizabeth.brown',
                'role' => 'Teacher',
            ],

            [
                'user_id' => null,
                'staff_number' => 'STF-2026-005',
                'first_name' => 'Daniel',
                'middle_name' => null,
                'last_name' => 'Adeyemi',
                'department_code' => 'SCI',
                'gender' => 'male',
                'date_of_birth' => '1985-06-30',
                'nationality' => 'Nigerian',
                'phone' => '+2348000000005',
                'staff_type' => 'Teaching',
                'employment_date' => '2020-09-01',
                'status' => 'active',
                'specialization' => 'Chemistry',
                'qualification' => 'B.Sc. Chemistry, M.Sc. Chemistry',
                'email' => 'daniel.adeyemi@greenfieldschool.test',
                'username' => 'daniel.adeyemi',
                'role' => 'Teacher',
            ],

            [
                'user_id' => null,
                'staff_number' => 'STF-2026-006',
                'first_name' => 'Grace',
                'middle_name' => 'Mary',
                'last_name' => 'Johnson',
                'department_code' => 'HUM',
                'gender' => 'female',
                'date_of_birth' => '1989-09-12',
                'nationality' => 'Nigerian',
                'phone' => '+2348000000006',
                'staff_type' => 'Teaching',
                'employment_date' => '2022-09-05',
                'status' => 'active',
                'specialization' => 'Social Studies',
                'qualification' => 'B.A. History, PGDE',
                'email' => 'grace.johnson@greenfieldschool.test',
                'username' => 'grace.johnson',
                'role' => 'Teacher',
            ],
        ];

        /*
        |--------------------------------------------------------------------------
        | Resolve Teacher Role
        |--------------------------------------------------------------------------
        */

        $teacherRole = Role::where('name', 'Teacher')
            ->where('guard_name', 'web')
            ->first();

        if (!$teacherRole) {
            throw new \RuntimeException(
                'Teacher role was not found for the web guard.'
            );
        }

        foreach ($staffMembers as $member) {

            /*
            |--------------------------------------------------------------------------
            | Resolve or create User
            |--------------------------------------------------------------------------
            */

            $userId = $member['user_id'] ?? null;

            if (!$userId) {

                $existingUser = DB::table('users')
                    ->where('email', $member['email'])
                    ->first();

                if ($existingUser) {

                    $userId = $existingUser->id;

                } else {

                    $userId = DB::table('users')->insertGetId([
                        'school_id' => $schoolId,
                        'user_type_id' => 4,
                        'designation_id' => 2,
                        'name' => $member['first_name'] . ' ' . $member['last_name'],
                        'email' => $member['email'],
                        'username' => $member['username'],
                        'email_verified_at' => now(),
                        'avatar' => 'default.png',
                        'is_active' => 1,
                        'is_staff' => 1,
                        'password' => Hash::make('ChangeMe@12345'),
                        'status' => 1,
                        'is_deleted' => 0,
                        'timezone' => 'Africa/Lagos',
                        'created_by' => 2,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }

            /*
            |--------------------------------------------------------------------------
            | Assign Staff User Role
            |--------------------------------------------------------------------------
            |
            | The staff record may already exist, and the user may already
            | exist. Therefore we explicitly ensure the Teacher role exists
            | for every staff member defined with that role.
            |
            */

            if (!empty($member['role'])) {

                $role = Role::where('name', $member['role'])
                    ->where('guard_name', 'web')
                    ->first();

                if (!$role) {
                    throw new \RuntimeException(
                        "Role {$member['role']} was not found for the web guard."
                    );
                }

                $roleAlreadyAssigned = DB::table('model_has_roles')
                    ->where('role_id', $role->id)
                    ->where('model_type', 'App\\Models\\User')
                    ->where('model_id', $userId)
                    ->exists();

                if (!$roleAlreadyAssigned) {
                    DB::table('model_has_roles')->insert([
                        'role_id' => $role->id,
                        'model_type' => 'App\\Models\\User',
                        'model_id' => $userId,
                    ]);
                }
            }

            /*
            |--------------------------------------------------------------------------
            | Department
            |--------------------------------------------------------------------------
            */

            $departmentId = DB::table('departments')
                ->where('school_id', $schoolId)
                ->where('code', $member['department_code'])
                ->value('id');

            if (!$departmentId) {
                throw new \RuntimeException(
                    "Department {$member['department_code']} was not found."
                );
            }

            /*
            |--------------------------------------------------------------------------
            | Staff
            |--------------------------------------------------------------------------
            */

            $existingStaff = DB::table('staff')
                ->where('staff_number', $member['staff_number'])
                ->first();

            $staffData = [
                'school_id' => $schoolId,
                'user_id' => $userId,
                'department_id' => $departmentId,
                'staff_number' => $member['staff_number'],
                'first_name' => $member['first_name'],
                'middle_name' => $member['middle_name'],
                'last_name' => $member['last_name'],
                'photo' => null,
                'date_of_birth' => $member['date_of_birth'],
                'nationality' => $member['nationality'],
                'gender' => $member['gender'],
                'phone' => $member['phone'],
                'staff_type' => $member['staff_type'],
                'employment_date' => $member['employment_date'],
                'status' => $member['status'],
                'deleted_at' => null,
                'updated_at' => now(),
            ];

            if ($existingStaff) {

                DB::table('staff')
                    ->where('id', $existingStaff->id)
                    ->update($staffData);

                $staffId = $existingStaff->id;

            } else {

                $staffData['created_at'] = now();

                $staffId = DB::table('staff')
                    ->insertGetId($staffData);
            }

            /*
            |--------------------------------------------------------------------------
            | Instructor
            |--------------------------------------------------------------------------
            */

            $instructorData = [
                'staff_id' => $staffId,
                'specialization' => $member['specialization'],
                'qualification' => $member['qualification'],
                'deleted_at' => null,
                'updated_at' => now(),
            ];

            $existingInstructor = DB::table('instructors')
                ->where('staff_id', $staffId)
                ->first();

            if ($existingInstructor) {

                DB::table('instructors')
                    ->where('id', $existingInstructor->id)
                    ->update($instructorData);

            } else {

                $instructorData['created_at'] = now();

                DB::table('instructors')
                    ->insert($instructorData);
            }
        }

        $this->command?->info(
            'Staff, instructor, user, and Teacher role reference data seeded successfully.'
        );
    }
}