<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_with_users_view_permission_can_open_user_list(): void
    {
        $admin = User::factory()->create();
        $role = Role::findOrCreate('Administrator', 'web');
        $role->givePermissionTo('users.view');
        $admin->assignRole($role);

        $response = $this->actingAs($admin)->get(route('admin.users.index'));

        $response->assertOk();
    }

    public function test_user_without_users_view_permission_is_forbidden(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('admin.users.index'));

        $response->assertForbidden();
    }

    public function test_super_admin_cannot_delete_self(): void
    {
        $admin = User::factory()->create();
        $role = Role::findOrCreate('Super Admin', 'web');
        $admin->assignRole($role);

        $response = $this->actingAs($admin)
            ->delete(route('admin.users.destroy', $admin));

        $response->assertStatus(422);
    }
}
