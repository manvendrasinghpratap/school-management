<?php

namespace App\Http\Controllers\Admin\Wave4;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Model;

abstract class SchoolScopedController extends Controller
{
    protected function schoolId(): int
    {
        $user = auth()->user();
        abort_unless($user && $user->school_id, 403, 'No school is assigned to the current user.');
        return (int) $user->school_id;
    }

    protected function ensureSchool(Model $model, string $column = 'school_id'): void
    {
        abort_unless((int) $model->{$column} === $this->schoolId(), 403, 'You are not authorized to access this record.');
    }

    protected function schoolUsers()
    {
        return \App\Models\User::query()->where('school_id', $this->schoolId())->where('is_deleted', false)->orderBy('name')->get();
    }
}
