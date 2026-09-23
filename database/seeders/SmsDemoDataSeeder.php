<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class SmsDemoDataSeeder extends Seeder
{
    /**
     * Seed a clean, coherent SMS demo dataset.
     *
     * Seeders are intentionally executed in dependency order.
     */
    public function run(): void
    {
        $this->command?->info('Seeding academic foundation...');

        $this->call([
            AcademicSetupSeeder::class,
        ]);

        $this->command?->info('Seeding staff...');

        $this->call([
            StaffSeeder::class,
        ]);

        $this->command?->info('Seeding students, guardians and enrollments...');

        $this->call([
            StudentSeeder::class,
        ]);

        $this->command?->info('Seeding scholarships...');

        $this->call([
            ScholarshipSeeder::class,
        ]);

        $this->command?->info('Seeding finance...');

        $this->call([
            FinanceDemoDataSeeder::class,
        ]);
    }
}