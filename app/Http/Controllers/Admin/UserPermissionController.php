<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserPermissionController extends Controller
{
    public function edit(User $user): View
    {
        $permissions = Permission::query()->orderBy('name')->get();
        $user->load('permissions');

        return view('admin.users.permissions', compact('user', 'permissions'));
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $data = $request->validate([
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['string', 'exists:permissions,name'],
        ]);

        $user->syncPermissions($data['permissions'] ?? []);

        return back()->with('success', 'Direct user permissions updated successfully.');
    }
}
