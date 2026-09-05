<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\UserManagementService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class UserPasswordController extends Controller
{
    public function __construct(
        private readonly UserManagementService $users
    ) {}

    public function update(Request $request, User $user): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $this->users->changePassword($user, $request->string('password')->toString());

        return back()->with('success', 'Password changed successfully.');
    }
}
