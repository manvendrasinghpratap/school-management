<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SystemSettingsRequest;
use App\Models\School;
use App\Services\SchoolSetupService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SystemSettingsController extends Controller
{
    public function __construct(
        private readonly SchoolSetupService $schoolSetup
    ) {
    }

    public function edit(School $school): View
    {
        $this->authorizeSchool($school);

        $settings = $school->settings()
    ->whereIn('setting_key', [
        'timezone',
        'date_format',
        'currency',
        'language',
        'attendance_enabled',
        'grading_enabled',
    ])
    ->pluck('setting_value', 'setting_key');

        return view(
            'admin.schools.settings',
            compact('school', 'settings')
        );
    }

    public function update(
        SystemSettingsRequest $request,
        School $school
    ): RedirectResponse {
        $this->authorizeSchool($school);

        $this->schoolSetup->updateSystemSettings(
            $school,
            $request->validated()
        );

        return back()->with(
            'success',
            'System settings updated successfully.'
        );
    }

    protected function authorizeSchool(School $school): void
    {
        $user = auth()->user();

        abort_unless($user, 403);

        if ($user->hasRole('Super Admin')) {
            return;
        }

        abort_unless(
            (int) $user->school_id === (int) $school->id,
            403
        );
    }
}