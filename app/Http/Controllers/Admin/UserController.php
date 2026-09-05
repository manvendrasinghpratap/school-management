<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UserStoreRequest;
use App\Http\Requests\Admin\UserUpdateRequest;
use App\Models\Role;
use App\Models\User;
use App\Services\UserManagementService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserController extends Controller
{
    public function __construct(
        private readonly UserManagementService $users
    ) {}

    public function index(Request $request): View
    {
        $query = User::query()->with('roles')->latest();

        if ($search = $request->string('search')->trim()->toString()) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($role = $request->string('role')->trim()->toString()) {
            $query->role($role);
        }

        if ($request->filled('status') && \Schema::hasColumn('users', 'is_active')) {
            $query->where('is_active', $request->boolean('status'));
        }

        $users = $query->paginate(20)->withQueryString();
        $roles = Role::query()->orderBy('name')->get();

        return view('admin.users.index', compact('users', 'roles'));
    }

    public function create(): View
    {
        $roles = Role::query()->orderBy('name')->get();

        return view('admin.users.create', compact('roles'));
    }

    public function store(UserStoreRequest $request): RedirectResponse
    {
        $user = $this->users->create($request->validated());

        return redirect()->route('admin.users.index')
            ->with('success', "User {$user->name} created successfully.");
    }

    public function edit(User $user): View
    {
        $roles = Role::query()->orderBy('name')->get();
        $user->load('roles');

        return view('admin.users.edit', compact('user', 'roles'));
    }

    public function update(UserUpdateRequest $request, User $user): RedirectResponse
    {
        abort_if(
            $user->id === $request->user()->id &&
            empty($request->input('roles')) &&
            $user->hasRole('Super Admin'),
            422,
            'You cannot remove your own Super Admin access.'
        );

        $this->users->update($user, $request->validated());

        return redirect()->route('admin.users.index')
            ->with('success', 'User updated successfully.');
    }

    public function destroy(Request $request, User $user): RedirectResponse
    {
        abort_if($user->id === $request->user()->id, 422, 'You cannot delete your own account.');

        abort_if(
            $user->hasRole('Super Admin') &&
            User::role('Super Admin')->count() <= 1,
            422,
            'The last Super Admin cannot be deleted.'
        );

        $user->delete();

        return back()->with('success', 'User deleted successfully.');
    }

    public function activate(Request $request, User $user): RedirectResponse
    {
        abort_unless(\Schema::hasColumn('users', 'is_active'), 422, 'Add an is_active column to users before using account activation.');

        $this->users->activate($user);

        return back()->with('success', 'User account activated.');
    }

    public function deactivate(Request $request, User $user): RedirectResponse
    {
        abort_if($user->id === $request->user()->id, 422, 'You cannot deactivate your own account.');
        abort_if($user->hasRole('Super Admin') && User::role('Super Admin')->count() <= 1, 422, 'The last Super Admin cannot be deactivated.');

        abort_unless(\Schema::hasColumn('users', 'is_active'), 422, 'Add an is_active column to users before using account activation.');

        $this->users->deactivate($user);

        return back()->with('success', 'User account deactivated.');
    }
}
