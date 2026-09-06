<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class UserPermissionController extends Controller
{
    /**
     * Display the permissions assigned directly to a user.
     */
    public function edit(User $user)
    {
        $this->ensureSameSchool($user);

        $permissions = Permission::query()
            ->where('guard_name', 'web')
            ->orderBy('name')
            ->get()
            ->groupBy(function ($permission) {
                return $this->permissionGroup($permission->name);
            });

        $user->load('roles');

        /*
         * Only direct permissions are selected here.
         *
         * Permissions inherited through roles are NOT selected.
         */
        $directPermissions = $user->getDirectPermissions()
            ->pluck('name')
            ->toArray();

        return view(
            'admin.users.permissions',
            compact(
                'user',
                'permissions',
                'directPermissions'
            )
        );
    }


    /**
     * Update direct permissions assigned to a user.
     */
    public function update(Request $request, User $user)
    {
        $this->ensureSameSchool($user);

        $validated = $request->validate([
            'permissions' => [
                'nullable',
                'array',
            ],

            'permissions.*' => [
                'integer',
                'exists:permissions,id',
            ],
        ]);

        $permissionIds = $validated['permissions'] ?? [];

        $permissions = Permission::query()
            ->where('guard_name', 'web')
            ->whereIn('id', $permissionIds)
            ->get();

        /*
         * Synchronize ONLY direct permissions.
         *
         * User roles remain untouched.
         */
        $user->syncPermissions($permissions);

        return redirect()
            ->route('admin.users.permissions.edit', $user)
            ->with(
                'success',
                'User permissions updated successfully.'
            );
    }


    /**
     * Group permissions for display.
     */
    protected function permissionGroup(string $permission): string
    {
        $parts = explode('.', $permission);

        if (count($parts) >= 2) {
            return ucfirst(
                str_replace(
                    ['-', '_'],
                    ' ',
                    $parts[0]
                )
            );
        }

        return 'Other';
    }


    /**
     * Ensure the user belongs to the current school.
     */
    protected function ensureSameSchool(User $user): void
    {
        $schoolId = auth()->user()?->school_id;

        if (!$schoolId) {
            abort(
                403,
                'No school is assigned to this user.'
            );
        }

        if ((int) $user->school_id !== (int) $schoolId) {
            abort(
                403,
                'You are not authorized to access this user.'
            );
        }
    }
}