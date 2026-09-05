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
        $permissions = Permission::query()->orderBy('name')->get();

        return view('admin.roles.create', compact('permissions'));
    }

    public function store(RoleRequest $request): RedirectResponse
    {
        $role = Role::create([
            'name' => $request->string('name')->toString(),
            'guard_name' => 'web',
        ]);

        $role->syncPermissions($request->input('permissions', []));

        return redirect()->route('admin.roles.index')
            ->with('success', 'Role created successfully.');
    }

    public function edit(Role $role): View
    {
        $permissions = Permission::query()->orderBy('name')->get();
        $role->load('permissions');

        return view('admin.roles.edit', compact('role', 'permissions'));
    }

    public function update(RoleRequest $request, Role $role): RedirectResponse
    {
        abort_if($role->name === 'Super Admin' && $request->string('name')->toString() !== 'Super Admin', 422, 'The Super Admin role cannot be renamed.');

        $role->update([
            'name' => $request->string('name')->toString(),
        ]);

        $role->syncPermissions($request->input('permissions', []));

        return redirect()->route('admin.roles.index')
            ->with('success', 'Role updated successfully.');
    }

    public function destroy(Role $role): RedirectResponse
    {
        abort_if($role->name === 'Super Admin', 422, 'The Super Admin role cannot be deleted.');

        $role->delete();

        return back()->with('success', 'Role deleted successfully.');
    }
}
