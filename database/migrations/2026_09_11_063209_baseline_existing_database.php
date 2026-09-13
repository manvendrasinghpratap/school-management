<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Mark the existing database schema as already established.
     *
     * This database was created before Laravel migration history
     * was properly maintained. The original migration files are
     * preserved in database/migrations_legacy/.
     *
     * IMPORTANT:
     * This migration intentionally does not create, alter, or drop
     * any application tables.
     */
    public function up(): void
    {
        $legacyMigrations = [
            '2026_09_05_000001_create_schools_table',
            '2026_09_05_000002_create_users_table',
            '2026_09_05_000003_create_roles_table',
            '2026_09_05_000004_create_permissions_table',
            '2026_09_05_000005_create_role_has_permissions_table',
            '2026_09_05_000006_create_model_has_roles_table',
            '2026_09_05_000007_create_settings_table',
            '2026_09_05_000008_create_academic_years_table',
            '2026_09_05_000009_create_terms_table',
            '2026_09_05_000010_create_departments_table',
            '2026_09_05_000011_create_levels_table',
            '2026_09_05_000012_create_classes_table',
            '2026_09_05_000013_create_sections_table',
            '2026_09_05_000014_create_students_table',
            '2026_09_05_000015_create_guardians_table',
            '2026_09_05_000016_create_student_guardians_table',
            '2026_09_05_000017_create_student_documents_table',
            '2026_09_05_000018_create_student_enrollments_table',
            '2026_09_05_000019_create_student_promotions_table',
            '2026_09_05_000020_create_staff_table',
            '2026_09_05_000021_create_instructors_table',
            '2026_09_05_000022_create_staff_documents_table',
            '2026_09_05_000023_create_courses_table',
            '2026_09_05_000024_create_course_assignments_table',
            '2026_09_05_000025_create_student_courses_table',
            '2026_09_05_000026_create_timetables_table',
            '2026_09_05_000027_create_attendance_table',
            '2026_09_05_000028_create_staff_attendance_table',
            '2026_09_05_000029_create_leaves_table',
            '2026_09_05_000030_create_examinations_table',
            '2026_09_05_000031_create_exam_schedules_table',
            '2026_09_05_000032_create_grades_table',
            '2026_09_05_000033_create_marks_table',
            '2026_09_05_000034_create_results_table',
            '2026_09_05_000035_create_report_cards_table',
            '2026_09_05_000036_create_transcripts_table',
            '2026_09_05_000037_create_fee_categories_table',
            '2026_09_05_000038_create_fee_structures_table',
            '2026_09_05_000039_create_scholarships_table',
            '2026_09_05_000040_create_student_fees_table',
            '2026_09_05_000041_create_invoices_table',
            '2026_09_05_000042_create_invoice_items_table',
            '2026_09_05_000043_create_payments_table',
            '2026_09_05_000044_create_receipts_table',
            '2026_09_05_000045_create_announcements_table',
            '2026_09_05_000046_create_notifications_table',
            '2026_09_05_000047_create_id_cards_table',
            '2026_09_05_000048_create_certificates_table',
            '2026_09_05_000049_create_alumni_table',
            '2026_09_05_000050_create_graduations_table',
            '2026_09_05_000051_create_events_table',
            '2026_09_05_000052_create_complaints_table',
            '2026_09_05_000053_create_visitors_table',
            '2026_09_05_000054_create_disciplinary_records_table',
            '2026_09_05_000055_create_medical_records_table',
            '2026_09_05_000056_create_suppliers_table',
            '2026_09_05_000057_create_inventory_items_table',
            '2026_09_05_000058_create_assets_table',
            '2026_09_05_000059_create_books_table',
            '2026_09_05_000060_create_book_issues_table',
            '2026_09_05_000061_create_vehicles_table',
            '2026_09_05_000062_create_routes_table',
            '2026_09_05_000063_create_route_students_table',
            '2026_09_05_000064_create_hostels_table',
            '2026_09_05_000065_create_rooms_table',
            '2026_09_05_000066_create_hostel_allocations_table',
            '2026_09_05_000067_create_audit_logs_table',
            '2026_09_05_000068_create_password_reset_tokens_table',
            '2026_09_05_000069_create_sessions_table',
            '2026_09_05_000070_create_model_has_permissions_table',
            '2026_09_05_000071_add_is_active_to_users_table',
            '2026_09_05_104008_create_model_has_permissions_table',
            '2026_09_05_124440_fix_students_user_foreign_keys',
        ];

        /*
         * The baseline migration itself is the migration record
         * representing the existing database.
         *
         * We intentionally do not insert the 73 legacy migration
         * records individually. They have been archived and will
         * no longer be executed by Laravel.
         */
    }

    /**
     * The existing database is the baseline, so there is nothing
     * to reverse here.
     */
    public function down(): void
    {
        // Intentionally empty.
    }
};