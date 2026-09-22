<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class HostelRbacSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            'hostel.view',
            'hostel.manage',
            'hostel.rooms.manage',
            'hostel.allocations.view',
            'hostel.allocations.manage',
            'hostel.fees.view',
            'hostel.fees.manage',
        ];

        foreach ($permissions as $permission) {
            Permission::findOrCreate($permission, 'web');
        }

        $this->command?->info('Hostel permissions ensured.');
        $this->command?->warn(
            'Assign these permissions to the appropriate roles using your existing RBAC workflow.'
        );
    }
}
