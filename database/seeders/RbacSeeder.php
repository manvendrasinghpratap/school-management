<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Spatie\Permission\PermissionRegistrar;

class RbacSeeder extends Seeder
{
    public function run(): void
    {
        $guard = 'web';

        /*
        |--------------------------------------------------------------------------
        | Permissions
        |--------------------------------------------------------------------------
        */

        $permissions = [

            // Dashboard
            'dashboard.view',

            // Users
            'users.view',
            'users.create',
            'users.update',
            'users.delete',
            'users.assign-roles',
            'users.assign-permissions',

            // Roles & permissions
            'roles.view',
            'roles.create',
            'roles.update',
            'roles.delete',
            'permissions.view',
            'permissions.create',
            'permissions.update',
            'permissions.delete',

            // School / academic setup
            'schools.view',
            'schools.create',
            'schools.update',

            'academic-years.view',
            'academic-years.create',
            'academic-years.update',
            'academic-years.delete',

            'terms.view',
            'terms.create',
            'terms.update',
            'terms.delete',

            'departments.view',
            'departments.create',
            'departments.update',
            'departments.delete',

            'classes.view',
            'classes.create',
            'classes.update',
            'classes.delete',

            'sections.view',
            'sections.create',
            'sections.update',
            'sections.delete',

            // Students
            'students.view',
            'students.create',
            'students.update',
            'students.delete',
            'students.export',

            'students.documents.view',
            'students.documents.manage',

            // Guardians
            'guardians.view',
            'guardians.create',
            'guardians.update',
            'guardians.delete',

            // Student Enrollment
            'enrollments.view',
            'enrollments.create',
            'enrollments.update',
            'enrollments.delete',

            // Student Course Registration
            'student-courses.view',
            'student-courses.create',
            'student-courses.update',
            'student-courses.delete',

            // Student Promotion
            'promotions.view',
            'promotions.create',
            'promotions.update',
            'promotions.delete',
            'promotions.approve',
            'promotions.reject',

            // Graduation
            'graduation.view',
            'graduation.create',
            'graduation.approve',
            'graduation.complete',
            'graduation.delete',

            // Alumni
            'alumni.view',
            'alumni.manage',

            // Staff / courses
            'staff.view',
            'staff.create',
            'staff.update',
            'staff.delete',

            'courses.view',
            'courses.create',
            'courses.update',
            'courses.delete',

            'instructors.view',
            'instructors.create',
            'instructors.update',
            'instructors.delete',

            'course-assignments.view',
            'course-assignments.manage',

            'timetable.view',
            'timetable.manage',

            // Attendance
            'attendance.view',
            'attendance.mark',
            'attendance.update',
            'attendance.reports',

            'staff-attendance.view',
            'staff-attendance.mark',

            'leave.view',
            'leave.manage',
            'leave.approve',

            // Examination
            'examinations.view',
            'examinations.create',
            'examinations.update',
            'examinations.delete',

            'exam-schedules.view',
            'exam-schedules.manage',

            'marks.view',
            'marks.enter',
            'marks.update',
            'marks.approve',

            'grading.view',
            'grading.manage',

            'results.view',
            'results.calculate',
            'results.approve',
            'results.publish',

            'report-cards.view',
            'report-cards.generate',

            'transcripts.view',
            'transcripts.generate',

            // Finance
            'fees.view',
            'fees.manage',

            'fee-structures.view',
            'fee-structures.manage',

            'fee-installments.view',
            'fee-installments.manage',

            'scholarships.view',
            'scholarships.manage',

            'invoices.view',
            'invoices.manage',
            'invoices.create',
            'invoices.update',
            'invoices.delete',

            'payments.view',
            'payments.create',
            'payments.reverse',

            // Payment Refunds / Reversals
            'payment-refunds.view',
            'payment-refunds.create',
            'payment-refunds.approve',
            'payment-refunds.reject',
            'payment-refunds.process',
            'payment-refunds.cancel',

            'receipts.view',
            'receipts.generate',

            'finance.reports',

            // Communication / identity
            'announcements.view',
            'announcements.manage',

            'notifications.view',
            'notifications.send',

            'id-cards.view',
            'id-cards.generate',

            'certificates.view',
            'certificates.generate',

            // Optional services
            'library.view',
            'library.manage',

            'transport.view',
            'transport.manage',

            'hostel.view',
            'hostel.manage',

            'inventory.view',
            'inventory.manage',

            'discipline.view',
            'discipline.manage',

            'medical.view',
            'medical.manage',

            'events.view',
            'events.manage',

            'complaints.view',
            'complaints.manage',

            'visitors.view',
            'visitors.manage',

            // System
            'reports.view',
            'audit-logs.view',

            'settings.view',
            'settings.update',

            'backup.view',
            'backup.create',

            'api.access',
        ];

        /*
        |--------------------------------------------------------------------------
        | Create / update permissions
        |--------------------------------------------------------------------------
        */

        foreach ($permissions as $name) {
            Permission::findOrCreate($name, $guard);
        }

        /*
        |--------------------------------------------------------------------------
        | Role Permissions
        |--------------------------------------------------------------------------
        */

        $rolePermissions = [

            /*
            |--------------------------------------------------------------------------
            | Super Admin
            |--------------------------------------------------------------------------
            */

            'Super Admin' => $permissions,

            /*
            |--------------------------------------------------------------------------
            | Administrator
            |--------------------------------------------------------------------------
            |
            | Administrator receives everything except:
            | - backup.*
            | - api.access
            |
            */

            'Administrator' => array_values(array_filter(
                $permissions,
                fn ($p) =>
                    !str_starts_with($p, 'backup.')
                    && $p !== 'api.access'
            )),

            /*
            |--------------------------------------------------------------------------
            | Principal
            |--------------------------------------------------------------------------
            |
            | Principal can review and approve/reject promotions.
            | Principal cannot create, update, or delete promotion records.
            |
            */

            'Principal' => [

                'dashboard.view',

                'students.view',

                'guardians.view',

                'staff.view',

                'courses.view',

                'student-courses.view',

                'attendance.view',
                'attendance.reports',

                'examinations.view',

                'marks.view',

                'results.view',
                'results.approve',
                'results.publish',

                'report-cards.view',
                'report-cards.generate',

                'transcripts.view',
                'transcripts.generate',

                'finance.reports',

                'reports.view',

                'announcements.view',
                'announcements.manage',

                'events.view',
                'events.manage',

                // Student Promotion
                'promotions.view',
                'promotions.approve',
                'promotions.reject',

                'invoices.view',
                'invoices.manage',
            ],

            /*
            |--------------------------------------------------------------------------
            | Examinations Officer
            |--------------------------------------------------------------------------
            */

            'Examinations Officer' => [

                'dashboard.view',

                'students.view',

                'courses.view',

                // Student Course Registration
                'student-courses.view',
                'student-courses.create',
                'student-courses.update',

                // Examination
                'examinations.view',
                'examinations.create',
                'examinations.update',

                'exam-schedules.view',
                'exam-schedules.manage',

                'marks.view',
                'marks.enter',
                'marks.update',
                'marks.approve',

                'grading.view',
                'grading.manage',

                'results.view',
                'results.calculate',
                'results.approve',
                'results.publish',

                'report-cards.view',
                'report-cards.generate',

                'transcripts.view',
                'transcripts.generate',
            ],

            /*
            |--------------------------------------------------------------------------
            | Teacher
            |--------------------------------------------------------------------------
            */

            'Teacher' => [

                'dashboard.view',

                'students.view',
                'guardians.view',

                'courses.view',

                // Student Course Registration - view only
                'student-courses.view',

                'course-assignments.view',

                'timetable.view',

                // Student Attendance
                'attendance.view',
                'attendance.mark',
                'attendance.update',

                // Marks
                'marks.view',
                'marks.enter',
                'marks.update',

                'examinations.view',

                'exam-schedules.view',

                'results.view',

                'report-cards.view',

                'announcements.view',
            ],

            /*
            |--------------------------------------------------------------------------
            | Accountant
            |--------------------------------------------------------------------------
            */

            'Accountant' => [

                'dashboard.view',

                'students.view',

                // Fees
                'fees.view',
                'fees.manage',

                // Fee Structures
                'fee-structures.view',
                'fee-structures.manage',

                // Fee Installments
                'fee-installments.view',
                'fee-installments.manage',

                // Scholarships / Discounts
                'scholarships.view',
                'scholarships.manage',

                // Invoices
                'invoices.view',
                'invoices.manage',
                'invoices.create',
                'invoices.update',

                // Payments
                'payments.view',
                'payments.create',

                // Payment Refunds / Reversals
                'payment-refunds.view',
                'payment-refunds.create',

                // Receipts
                'receipts.view',
                'receipts.generate',

                // Finance Reports
                'finance.reports',
            ],

            /*
            |--------------------------------------------------------------------------
            | Registrar
            |--------------------------------------------------------------------------
            |
            | Registrar manages student registration-related records.
            | Registrar cannot delete course registrations.
            |
            */

            'Registrar' => [

                'dashboard.view',

                'students.view',
                'students.create',
                'students.update',
                'students.export',

                'students.documents.view',
                'students.documents.manage',

                'guardians.view',
                'guardians.create',
                'guardians.update',

                'enrollments.view',
                'enrollments.create',
                'enrollments.update',

                // Student Course Registration
                'student-courses.view',
                'student-courses.create',
                'student-courses.update',

                // Student Promotion
                'promotions.view',
                'promotions.create',
                'promotions.update',

                'graduation.view',
                'graduation.manage',

                'alumni.view',
                'alumni.manage',

                'report-cards.view',

                'transcripts.view',
                'transcripts.generate',

                'id-cards.view',
                'id-cards.generate',
            ],

            /*
            |--------------------------------------------------------------------------
            | Student
            |--------------------------------------------------------------------------
            */

            'Student' => [

                'dashboard.view',

                'students.view',

                'courses.view',

                'timetable.view',

                'attendance.view',

                'marks.view',

                'results.view',

                'report-cards.view',

                'transcripts.view',

                'announcements.view',

                'notifications.view',

                'id-cards.view',
            ],

            /*
            |--------------------------------------------------------------------------
            | Parent
            |--------------------------------------------------------------------------
            */

            'Parent' => [

                'dashboard.view',

                'students.view',

                'attendance.view',

                'marks.view',

                'results.view',

                'report-cards.view',

                'announcements.view',

                'notifications.view',

                'fees.view',

                'invoices.view',

                'payments.view',

                'receipts.view',
            ],
        ];

        /*
        |--------------------------------------------------------------------------
        | Sync role permissions
        |--------------------------------------------------------------------------
        */

        foreach ($rolePermissions as $roleName => $rolePermissionNames) {

            $role = Role::findOrCreate($roleName, $guard);

            $role->syncPermissions($rolePermissionNames);
        }

        /*
        |--------------------------------------------------------------------------
        | Clear Spatie permission cache
        |--------------------------------------------------------------------------
        */

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }
}