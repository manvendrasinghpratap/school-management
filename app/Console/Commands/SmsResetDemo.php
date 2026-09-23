<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Throwable;

class SmsResetDemo extends Command
{
    protected $signature = 'sms:reset-demo
                            {--force : Skip the confirmation prompt}';

    protected $description = 'Reset SMS application data and prepare the database for clean demo data';

    /**
     * Tables containing application/business data.
     *
     * RBAC definition tables are intentionally NOT included here:
     * - permissions
     * - roles
     * - role_has_permissions
     * - model_has_permissions
     * - model_has_roles
     */
    private array $tables = [
        // Communication / events
        'event_participants',
        'announcement_recipients',
        'notifications',
        'events',
        'announcements',

        // Documents / certificates / ID cards
        'certificates',
        'certificate_templates',
        'id_cards',
        'id_card_templates',
        'student_documents',
        'staff_documents',

        // Hostel
        'hostel_allocations',
        'hostel_beds',
        'hostel_rooms',
        'hostels',

        // Transport
        'route_students',
        'transport_stops',
        'transport_routes',
        'vehicles',
        'transport_drivers',

        // Library
        'book_reservations',
        'book_issues',
        'book_copies',
        'book_author',
        'books',
        'library_members',
        'library_authors',
        'library_publishers',
        'library_categories',

        // Finance
        'payment_refunds',
        'payment_allocations',
        'finance_movements',
        'receipts',
        'payments',
        'invoice_items',
        'invoices',
        'student_fees',
        'fee_installments',
        'fee_structures',
        'fee_categories',
        'scholarships',

        // Examination / academic results
        'transcripts',
        'report_cards',
        'results',
        'marks',
        'grades',
        'exam_schedules',
        'examinations',

        // Attendance / leave
        'staff_attendance',
        'attendance',
        'leaves',

        // Academic activity
        'timetables',
        'student_courses',
        'course_assignments',
        'courses',

        // Student lifecycle
        'student_promotions',
        'graduations',
        'alumni',
        'student_enrollments',
        'student_guardians',
        'guardians',
        'students',

        // Staff
        'instructors',
        'staff',

        // Academic structure
        'sections',
        'classes',
        'levels',
        'terms',
        'academic_years',
        'departments',

        // Other school/business data
        'complaints',
        'disciplinary_records',
        'medical_records',
        'visitors',
        'inventory_items',
        'assets',
        'suppliers',
        'rooms',

        // Wave 4
        'admission_applications',
    ];

    public function handle(): int
    {
        $this->newLine();

        $this->info('========================================');
        $this->info(' SMS DEMO DATA RESET');
        $this->info('========================================');
        $this->newLine();

        $this->warn('This command will remove existing SMS business/demo data.');
        $this->warn('RBAC permission and role definitions will be preserved.');
        $this->newLine();

        if (! $this->option('force')) {
            if (! $this->confirm('Do you want to continue?', false)) {
                $this->warn('Reset cancelled. No data was changed.');

                return self::SUCCESS;
            }
        }

        try {
            DB::transaction(function () {
                $this->disableForeignKeys();

                $this->resetBusinessTables();

                $this->enableForeignKeys();
            });

            $this->newLine();
            $this->info('Business data reset completed.');
            $this->info('RBAC definitions were preserved.');
            $this->newLine();

            $this->warn('IMPORTANT: Clean demo-data seeding is the next step.');
            $this->warn('No new demo records have been generated yet.');

            return self::SUCCESS;
        } catch (Throwable $e) {
            $this->enableForeignKeys();

            $this->newLine();
            $this->error('SMS demo reset failed.');
            $this->error($e->getMessage());

            if ($this->getOutput()->isVerbose()) {
                $this->newLine();
                $this->error($e->getTraceAsString());
            }

            return self::FAILURE;
        }
    }

    private function resetBusinessTables(): void
    {
        foreach ($this->tables as $table) {
            if (! $this->tableExists($table)) {
                continue;
            }

            $count = DB::table($table)->count();

            if ($count === 0) {
                $this->line("  {$table}: already empty");

                continue;
            }

            DB::table($table)->delete();

            $this->line("  {$table}: deleted {$count} record(s)");
        }
    }

    private function disableForeignKeys(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
    }

    private function enableForeignKeys(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }

    private function tableExists(string $table): bool
    {
        return DB::getSchemaBuilder()->hasTable($table);
    }
}