<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Database\Seeders\SmsDemoDataSeeder;

class SmsSeedDemo extends Command
{
    protected $signature = 'sms:seed-demo';

    protected $description = 'Seed the School Management System with clean demo data';

    public function handle(): int
    {
        $this->info('========================================');
        $this->info(' SMS DEMO DATA SEED');
        $this->info('========================================');

        $this->call(SmsDemoDataSeeder::class);

        $this->newLine();
        $this->info('Clean SMS demo data seeded successfully.');

        return self::SUCCESS;
    }
}