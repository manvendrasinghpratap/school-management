<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreSchoolRequest;
use App\Http\Requests\Admin\UpdateSchoolRequest;
use App\Models\School;
use App\Services\SchoolService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SchoolController extends Controller
{
    public function __construct(
        protected SchoolService $schoolService
    ) {
    }

    /**
     * Show initial school setup form.
     */
    public function create(): View
    {
        abort_unless(
            School::count() === 0,
            403,
            'Initial school setup has already been completed.'
        );

        return view('admin.schools.create');
    }

    /**
     * Create the first school.
     */
    public function store(
        StoreSchoolRequest $request
    ): RedirectResponse {
        abort_unless(
            School::count() === 0,
            403,
            'Initial school setup has already been completed.'
        );

        $school = $this->schoolService->create(
            $request->validated(),
            $request->file('logo')
        );

        return redirect()
            ->route('admin.school.edit', $school)
            ->with(
                'success',
                'School created successfully.'
            );
    }

    /**
     * Show school profile.
     */
    public function edit(School $school): View
    {
        $this->authorizeSchool($school);

        return view(
            'admin.schools.edit',
            compact('school')
        );
    }

    /**
     * Update school profile.
     */
    public function update(
        UpdateSchoolRequest $request,
        School $school
    ): RedirectResponse {
        $this->authorizeSchool($school);

        $school = $this->schoolService->update(
            $school,
            $request->validated(),
            $request->file('logo')
        );

        return redirect()
            ->route('admin.school.edit', $school)
            ->with(
                'success',
                'School profile updated successfully.'
            );
    }

    /**
     * Ensure user can access this school.
     */
    protected function authorizeSchool(School $school): void
    {
        $user = auth()->user();

        abort_unless(
            $user,
            403
        );

        if ($user->hasRole('Super Admin')) {
            return;
        }

        abort_unless(
            (int) $user->school_id === (int) $school->id,
            403
        );
    }
}