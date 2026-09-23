<?php

namespace Database\Seeders;

use App\Models\Guardian;
use App\Models\Staff;
use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class Wave5PortalSeeder extends Seeder
{
    public function run(): void
    {
        $schoolId = (int) (\App\Models\School::query()->value('id') ?? 1);

        $student = Student::where('school_id', $schoolId)->where('status', 'active')->orderBy('id')->first();
        if ($student) {
            $user = User::updateOrCreate(
                ['email' => 'student.portal@greenfieldschool.test'],
                [
                    'school_id' => $schoolId,
                    'user_type_id' => 3,
                    'name' => $student->full_name,
                    'username' => 'student.portal',
                    'password' => Hash::make('Student@12345'),
                    'is_active' => true,
                    'is_staff' => false,
                    'status' => 1,
                    'is_deleted' => false,
                ]
            );
            $user->syncRoles(['Student']);
            $student->update(['user_id' => $user->id]);
        }

        $guardian = Guardian::where('school_id', $schoolId)->whereNull('user_id')->orderBy('id')->first();
        if ($guardian) {
            $user = User::updateOrCreate(
                ['email' => 'parent.portal@greenfieldschool.test'],
                [
                    'school_id' => $schoolId,
                    'user_type_id' => 3,
                    'name' => $guardian->full_name,
                    'username' => 'parent.portal',
                    'password' => Hash::make('Parent@12345'),
                    'is_active' => true,
                    'is_staff' => false,
                    'status' => 1,
                    'is_deleted' => false,
                ]
            );
            $user->syncRoles(['Parent']);
            $guardian->update(['user_id' => $user->id]);
        }

        $teacher = Staff::where('school_id', $schoolId)->whereNotNull('user_id')->where('status', 'active')->first();
        if ($teacher?->user) {
            $teacher->user->syncRoles(['Teacher']);
        }

        $this->command?->info('Wave 5 portal demo accounts prepared.');
    }
}
