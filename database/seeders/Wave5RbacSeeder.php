<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\PermissionRegistrar;

class Wave5RbacSeeder extends Seeder
{
    public function run(): void
    {
        $permission = Permission::findOrCreate('api.access', 'web');
        foreach (['Student', 'Parent', 'Teacher'] as $roleName) {
            $role = Role::findOrCreate($roleName, 'web');
            $role->givePermissionTo($permission);
        }
        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }
}
