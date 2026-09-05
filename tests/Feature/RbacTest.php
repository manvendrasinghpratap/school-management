<?php

namespace Tests\Feature;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RbacTest extends TestCase
{
    use RefreshDatabase;

    public function test_super_admin_can_access_every_permission(): void
    {
        $user = User::factory()->create();
        $role = Role::findOrCreate('Super Admin', 'web');
        $user->assignRole($role);

        $this->assertTrue($user->can('students.delete'));
        $this->assertTrue($user->can('settings.update'));
    }

    public function test_role_permission_is_inherited_by_user(): void
    {
        $user = User::factory()->create();
        $permission = Permission::findOrCreate('students.view', 'web');
        $role = Role::findOrCreate('Registrar', 'web');

        $role->givePermissionTo($permission);
        $user->assignRole($role);

        $this->assertTrue($user->can('students.view'));
        $this->assertFalse($user->can('students.delete'));
    }
}
