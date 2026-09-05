<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserManagementService
{
    public function create(array $data): User
    {
        return DB::transaction(function () use ($data) {
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
            ]);

            $user->syncRoles($data['roles'] ?? []);

            return $user->fresh(['roles']);
        });
    }

    public function update(User $user, array $data): User
    {
        return DB::transaction(function () use ($user, $data) {
            $payload = [
                'name' => $data['name'],
                'email' => $data['email'],
            ];

            if (!empty($data['password'])) {
                $payload['password'] = Hash::make($data['password']);
            }

            $user->update($payload);

            if (array_key_exists('roles', $data)) {
                $user->syncRoles($data['roles'] ?? []);
            }

            return $user->fresh(['roles']);
        });
    }

    public function activate(User $user): void
    {
        if ($this->hasActiveFlagColumn()) {
            $user->forceFill(['is_active' => true])->save();
        }
    }

    public function deactivate(User $user): void
    {
        if ($this->hasActiveFlagColumn()) {
            $user->forceFill(['is_active' => false])->save();
        }
    }

    public function changePassword(User $user, string $password): void
    {
        $user->forceFill([
            'password' => Hash::make($password),
        ])->save();
    }

    private function hasActiveFlagColumn(): bool
    {
        return \Schema::hasColumn('users', 'is_active');
    }
}
