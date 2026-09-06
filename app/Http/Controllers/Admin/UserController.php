<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function __construct(
        protected UserService $userService
    ) {
    }

    /**
     * Display users.
     */
    public function index(Request $request)
    {
        $schoolId = $this->schoolId();

        $query = User::query()
            ->where('school_id', $schoolId)
            ->where('is_deleted', false)
            ->with('roles');

        /*
         * Search
         */
        if ($request->filled('search')) {

            $search = trim($request->search);

            $query->where(function ($q) use ($search) {

                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('username', 'like', "%{$search}%");
            });
        }

        /*
         * Active / inactive filter
         */
        if ($request->filled('status')) {

            if ($request->status === 'active') {
                $query->where('is_active', true);
            }

            if ($request->status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        /*
         * Staff / non-staff filter
         */
        if ($request->filled('staff')) {

            if ($request->staff === 'yes') {
                $query->where('is_staff', true);
            }

            if ($request->staff === 'no') {
                $query->where('is_staff', false);
            }
        }

        $users = $query
            ->latest('id')
            ->paginate(15)
            ->withQueryString();

        /*
         * Dashboard statistics
         */
        $statistics = [

            'total' => User::query()
                ->where('school_id', $schoolId)
                ->where('is_deleted', false)
                ->count(),

            'active' => User::query()
                ->where('school_id', $schoolId)
                ->where('is_deleted', false)
                ->where('is_active', true)
                ->count(),

            'inactive' => User::query()
                ->where('school_id', $schoolId)
                ->where('is_deleted', false)
                ->where('is_active', false)
                ->count(),

            'staff' => User::query()
                ->where('school_id', $schoolId)
                ->where('is_deleted', false)
                ->where('is_staff', true)
                ->count(),
        ];

        return view(
            'admin.users.index',
            compact('users', 'statistics')
        );
    }

    /**
     * Show create form.
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Store new user.
     */
    public function store(StoreUserRequest $request)
    {
        $data = $request->validated();

        /*
         * Handle avatar upload.
         */
        if ($request->hasFile('avatar')) {

            $data['avatar'] = $request
                ->file('avatar')
                ->store('users/avatars', 'public');
        }

        $user = $this->userService->create($data);

        return redirect()
            ->route('admin.users.show', $user)
            ->with('success', 'User created successfully.');
    }

    /**
     * Display user.
     */
    public function show(User $user)
    {
        $this->ensureSameSchool($user);

        $user->load([
            'school',
            'staff',
            'roles',
        ]);

        return view(
            'admin.users.show',
            compact('user')
        );
    }

    /**
     * Show edit form.
     */
   public function edit(User $user)
{
    $this->ensureSameSchool($user);

    $roles = \Spatie\Permission\Models\Role::query()
        ->where('guard_name', 'web')
        ->orderBy('name')
        ->get();

    return view('admin.users.edit', compact('user', 'roles'));
}

    /**
     * Update user.
     */
    public function update(
        UpdateUserRequest $request,
        User $user
    ) {
        $this->ensureSameSchool($user);

        $data = $request->validated();

        /*
         * Handle avatar upload.
         */
        if ($request->hasFile('avatar')) {

            /*
             * Delete old avatar if it is a custom uploaded file.
             */
            if (
                $user->avatar &&
                $user->avatar !== 'default.png'
            ) {
                Storage::disk('public')
                    ->delete($user->avatar);
            }

            $data['avatar'] = $request
                ->file('avatar')
                ->store('users/avatars', 'public');
        }

        $this->userService->update(
            $user,
            $data
        );

        return redirect()
            ->route('admin.users.show', $user)
            ->with('success', 'User updated successfully.');
    }

    /**
     * Soft-delete user.
     */
    public function destroy(User $user)
    {
        $this->ensureSameSchool($user);

        $this->userService->delete($user);

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User deleted successfully.');
    }

    /**
     * Activate user.
     */
    public function activate(User $user)
    {
        $this->ensureSameSchool($user);

        $this->userService->activate($user);

        return back()
            ->with('success', 'User activated successfully.');
    }

    /**
     * Deactivate user.
     */
    public function deactivate(User $user)
    {
        $this->ensureSameSchool($user);

        $this->userService->deactivate($user);

        return back()
            ->with('success', 'User deactivated successfully.');
    }

    /**
     * Restore deleted user.
     */
    public function restore(User $user)
    {
        $this->ensureSameSchool($user);

        $this->userService->restore($user);

        return back()
            ->with('success', 'User restored successfully.');
    }

    /**
     * Get current school ID.
     */
    protected function schoolId(): int
    {
        $schoolId = auth()->user()?->school_id;

        if (!$schoolId) {
            abort(
                403,
                'No school is assigned to this user.'
            );
        }

        return (int) $schoolId;
    }

    /**
     * Verify user belongs to current school.
     */
    protected function ensureSameSchool(User $user): void
    {
        if ((int) $user->school_id !== $this->schoolId()) {
            abort(
                403,
                'You are not authorized to access this user.'
            );
        }
    }
}