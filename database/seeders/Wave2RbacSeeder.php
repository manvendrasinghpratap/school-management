<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class Wave2RbacSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            'library.view','library.create','library.update','library.delete',
            'library.members.view','library.members.manage',
            'library.issues.view','library.issues.manage',
            'library.reservations.view','library.reservations.manage',
            'transport.view','transport.manage','transport.assign',
            'transport.fees.view','transport.fees.manage',
            'hostel.view','hostel.manage','hostel.rooms.manage',
            'hostel.allocations.view','hostel.allocations.manage',
            'hostel.fees.view','hostel.fees.manage',
        ];

        foreach ($permissions as $name) {
            Permission::findOrCreate($name, 'web');
        }
    }
}
