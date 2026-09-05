<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserRoleController extends Controller
{
    public function edit(User $user): View
    {
        $roles = Role::query()->orderBy('name')->get();
        $user->load('roles');

        return view('admin.users.roles', compact('user', 'roles'));
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $data = $request->validate([
            'roles' => ['nullable', 'array'],
            'roles.*' => ['string', 'exists:roles,name'],
        ]);

        abort_if($user->id === $request->user()->id && !in_array('Super Admin', $data['roles'] ?? [], true), 422, 'You cannot remove your own Super Admin access.');

        $user->syncRoles($data['roles'] ?? []);

        return back()->with('success', 'User roles updated successfully.');
    }
}
