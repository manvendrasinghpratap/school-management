<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class UserService
{
    /**
     * Create a new user.
     */
    public function create(array $data): User
    {
        return DB::transaction(function () use ($data) {

            $schoolId = $this->schoolId();

            $this->validateUsername($data['username']);
            $this->validateEmail($data['email']);

            $user = User::create([
                'school_id'      => $schoolId,
                'user_type_id'   => $data['user_type_id'] ?? null,
                'designation_id' => $data['designation_id'] ?? null,

                'name'           => trim($data['name']),
                'email'          => strtolower(trim($data['email'])),
                'username'       => trim($data['username']),

                'avatar'         => $data['avatar'] ?? 'default.png',

                'is_active'      => $data['is_active'] ?? true,
                'is_staff'       => $data['is_staff'] ?? false,

                'password'       => $data['password'],

                'status'         => $data['status'] ?? 2,
                'is_deleted'     => false,

                'timezone'       => $data['timezone'] ?? 'Africa/Lagos',

                'created_by'     => Auth::id(),
            ]);

            /*
             * Assign roles using Spatie.
             *
             * The form sends role names:
             *
             * roles[] = "Administrator"
             * roles[] = "Teacher"
             *
             */
            if (array_key_exists('roles', $data)) {

                $roles = $this->cleanRoles($data['roles'] ?? []);

                $user->syncRoles($roles);
                
            }

            return $user->fresh(['roles']);
        });
    }


    /**
     * Update an existing user.
     */
    public function update(User $user, array $data): User
{
    return DB::transaction(function () use ($user, $data) {

        $this->ensureSameSchool($user);

        $this->validateUsername(
            $data['username'],
            $user->id
        );

        $this->validateEmail(
            $data['email'],
            $user->id
        );

        $updateData = [
            'user_type_id'   => $data['user_type_id'] ?? null,
            'designation_id' => $data['designation_id'] ?? null,

            'name'           => trim($data['name']),
            'email'          => strtolower(trim($data['email'])),
            'username'       => trim($data['username']),

            'is_active'      => $data['is_active'] ?? true,
            'is_staff'       => $data['is_staff'] ?? false,

            'status'         => $data['status'] ?? 2,

            'timezone'       => $data['timezone']
                ?? $user->timezone,
        ];

        if (!empty($data['password'])) {
            $updateData['password'] = $data['password'];
        }

        if (
            array_key_exists('avatar', $data)
            && !empty($data['avatar'])
        ) {
            $updateData['avatar'] = $data['avatar'];
        }

        $user->update($updateData);

        /*
         * Sync Spatie roles.
         *
         * The form sends role names.
         */
        if (array_key_exists('roles', $data)) {

            $roles = $this->cleanRoles(
                $data['roles'] ?? []
            );

            $user->syncRoles($roles);
        }

        return $user->fresh(['roles']);
    });
}


    /**
     * Delete a user.
     */
    public function delete(User $user): void
    {
        DB::transaction(function () use ($user) {

            $this->ensureSameSchool($user);

            if ((int) $user->id === (int) Auth::id()) {
                throw ValidationException::withMessages([
                    'user' => 'You cannot delete your own account.',
                ]);
            }

            $activeUsers = User::query()
                ->where('school_id', $user->school_id)
                ->where('is_deleted', false)
                ->where('is_active', true)
                ->count();

            if (
                $activeUsers <= 1
                && $user->is_active
            ) {
                throw ValidationException::withMessages([
                    'user' =>
                        'The last active user in the school cannot be deleted.',
                ]);
            }

            $user->update([
                'is_deleted' => true,
                'is_active'  => false,
            ]);
        });
    }


    /**
     * Restore a deleted user.
     */
    public function restore(User $user): User
    {
        $this->ensureSameSchool($user);

        $user->update([
            'is_deleted' => false,
            'is_active'  => true,
        ]);

        return $user->fresh(['roles']);
    }


    /**
     * Activate a user.
     */
    public function activate(User $user): User
    {
        $this->ensureSameSchool($user);

        if ($user->is_deleted) {
            throw ValidationException::withMessages([
                'user' =>
                    'A deleted user must be restored before activation.',
            ]);
        }

        $user->update([
            'is_active' => true,
        ]);

        return $user->fresh(['roles']);
    }


    /**
     * Deactivate a user.
     */
    public function deactivate(User $user): User
    {
        $this->ensureSameSchool($user);

        if ((int) $user->id === (int) Auth::id()) {
            throw ValidationException::withMessages([
                'user' =>
                    'You cannot deactivate your own account.',
            ]);
        }

        $user->update([
            'is_active' => false,
        ]);

        return $user->fresh(['roles']);
    }


    /**
     * Clean and validate role values.
     */
    protected function cleanRoles(array $roles): array
    {
        return collect($roles)
            ->filter(fn ($role) => is_string($role) && trim($role) !== '')
            ->map(fn ($role) => trim($role))
            ->unique()
            ->values()
            ->all();
    }


    /**
     * Validate username uniqueness.
     */
    protected function validateUsername(
        string $username,
        ?int $ignoreId = null
    ): void {

        $query = User::query()
            ->where('username', trim($username))
            ->where('is_deleted', false);

        if ($ignoreId !== null) {
            $query->where('id', '!=', $ignoreId);
        }

        if ($query->exists()) {
            throw ValidationException::withMessages([
                'username' =>
                    'This username is already in use.',
            ]);
        }
    }


    /**
     * Validate email uniqueness.
     */
    protected function validateEmail(
        string $email,
        ?int $ignoreId = null
    ): void {

        $query = User::query()
            ->where(
                'email',
                strtolower(trim($email))
            )
            ->where('is_deleted', false);

        if ($ignoreId !== null) {
            $query->where('id', '!=', $ignoreId);
        }

        if ($query->exists()) {
            throw ValidationException::withMessages([
                'email' =>
                    'This email address is already in use.',
            ]);
        }
    }


    /**
     * Ensure the user belongs to the current school.
     */
    protected function ensureSameSchool(User $user): void
    {
        $schoolId = $this->schoolId();

        if ((int) $user->school_id !== $schoolId) {
            abort(
                403,
                'You are not authorized to access this user.'
            );
        }
    }


    /**
     * Get current school ID.
     */
    protected function schoolId(): int
    {
        $schoolId = Auth::user()?->school_id;

        if (!$schoolId) {
            abort(
                403,
                'No school is assigned to this user.'
            );
        }

        return (int) $schoolId;
    }
}