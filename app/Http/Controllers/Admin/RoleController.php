<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\RoleRequest;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class RoleController extends Controller
{
    public function index(): View
    {
        $roles = Role::query()
            ->withCount('users')
            ->with('permissions')
            ->orderBy('name')
            ->paginate(20);

        return view('admin.roles.index', compact('roles'));
    }

    public function create(): View
    {
        $permissions = Permission::query()
            ->where('guard_name', 'web')
            ->orderBy('name')
            ->get();

        return view('admin.roles.create', compact('permissions'));
    }

    public function store(RoleRequest $request): RedirectResponse
    {
        $role = Role::create([
            'name' => $request->string('name')->toString(),
            'guard_name' => 'web',
        ]);

        $permissionIds = $request->input('permissions', []);

        $permissions = Permission::query()
            ->where('guard_name', 'web')
            ->whereIn('id', $permissionIds)
            ->get();

        $role->syncPermissions($permissions);

        return redirect()
            ->route('admin.roles.index')
            ->with('success', 'Role created successfully.');
    }

    public function show(Role $role): View
    {
        $role->load([
            'permissions',
            'users',
        ]);

        return view('admin.roles.show', compact('role'));
    }

    public function edit(Role $role): View
    {
        $permissions = Permission::query()
            ->where('guard_name', 'web')
            ->orderBy('name')
            ->get();

        $role->load('permissions');

        return view('admin.roles.edit', compact(
            'role',
            'permissions'
        ));
    }

    public function permissions(Role $role): View
{
    $permissions = Permission::query()
        ->where('guard_name', 'web')
        ->orderBy('name')
        ->get()
        ->groupBy(function ($permission) {
            $parts = explode('.', $permission->name);

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
        });

    $role->load('permissions');

    $assignedPermissionIds = $role->permissions
        ->pluck('id')
        ->map(fn ($id) => (string) $id)
        ->all();

    return view('admin.roles.permissions', compact(
        'role',
        'permissions',
        'assignedPermissionIds'
    ));
}

        public function updatePermissions(
        \Illuminate\Http\Request $request,
        Role $role
        ): RedirectResponse {
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

        $role->syncPermissions($permissions);

        return redirect()
            ->route('admin.roles.permissions.edit', $role)
            ->with('success', 'Role permissions updated successfully.');
        }


    public function update(
        RoleRequest $request,
        Role $role
    ): RedirectResponse {

        $newName = $request->string('name')->toString();

        abort_if(
            $role->name === 'Super Admin'
            && $newName !== 'Super Admin',
            422,
            'The Super Admin role cannot be renamed.'
        );

        $role->update([
            'name' => $newName,
        ]);

        $permissionIds = $request->input('permissions', []);

        $permissions = Permission::query()
            ->where('guard_name', 'web')
            ->whereIn('id', $permissionIds)
            ->get();

        $role->syncPermissions($permissions);

        return redirect()
            ->route('admin.roles.index')
            ->with('success', 'Role updated successfully.');
    }

    public function destroy(Role $role): RedirectResponse
    {
        abort_if(
            $role->name === 'Super Admin',
            422,
            'The Super Admin role cannot be deleted.'
        );

        $role->delete();

        return back()
            ->with('success', 'Role deleted successfully.');
    }
}