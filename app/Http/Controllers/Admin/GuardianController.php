<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreGuardianRequest;
use App\Http\Requests\Admin\UpdateGuardianRequest;
use App\Models\Guardian;
use App\Services\GuardianService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class GuardianController extends Controller
{
    public function __construct(
        protected GuardianService $guardianService
    ) {
    }

    public function index(Request $request): View
    {
        $schoolId = auth()->user()->school_id;

        $guardians = Guardian::query()
            ->where('school_id', $schoolId)
            ->withCount('students')
            ->when(
                $request->filled('search'),
                function ($query) use ($request) {
                    $search = $request->string('search');

                    $query->where(function ($query) use ($search) {
                        $query->where(
                            'guardian_number',
                            'like',
                            "%{$search}%"
                        )
                        ->orWhere(
                            'first_name',
                            'like',
                            "%{$search}%"
                        )
                        ->orWhere(
                            'middle_name',
                            'like',
                            "%{$search}%"
                        )
                        ->orWhere(
                            'last_name',
                            'like',
                            "%{$search}%"
                        )
                        ->orWhere(
                            'phone',
                            'like',
                            "%{$search}%"
                        )
                        ->orWhere(
                            'whatsapp',
                            'like',
                            "%{$search}%"
                        )
                        ->orWhere(
                            'email',
                            'like',
                            "%{$search}%"
                        );
                    });
                }
            )
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view(
            'admin.guardians.index',
            compact('guardians')
        );
    }

    public function create(): View
    {
        return view('admin.guardians.create');
    }

    public function store(
        StoreGuardianRequest $request
    ): RedirectResponse {
        $guardian = $this->guardianService->create(
            $request->validated()
        );

        return redirect()
            ->route('admin.guardians.show', $guardian)
            ->with(
                'success',
                'Guardian registered successfully.'
            );
    }

    public function show(Guardian $guardian): View
    {
        $this->authorizeGuardian($guardian);

        $guardian->load([
            'students',
        ]);

        return view(
            'admin.guardians.show',
            compact('guardian')
        );
    }

    public function edit(Guardian $guardian): View
    {
        $this->authorizeGuardian($guardian);

        return view(
            'admin.guardians.edit',
            compact('guardian')
        );
    }

    public function update(
        UpdateGuardianRequest $request,
        Guardian $guardian
    ): RedirectResponse {
        $this->authorizeGuardian($guardian);

        $guardian = $this->guardianService->update(
            $guardian,
            $request->validated()
        );

        return redirect()
            ->route('admin.guardians.show', $guardian)
            ->with(
                'success',
                'Guardian updated successfully.'
            );
    }

    public function destroy(
        Guardian $guardian
    ): RedirectResponse {
        $this->authorizeGuardian($guardian);

        $this->guardianService->delete($guardian);

        return redirect()
            ->route('admin.guardians.index')
            ->with(
                'success',
                'Guardian deleted successfully.'
            );
    }

    protected function authorizeGuardian(
        Guardian $guardian
    ): void {
        $user = auth()->user();

        abort_unless($user, 403);

        if ($user->hasRole('Super Admin')) {
            return;
        }

        abort_unless(
            (int) $guardian->school_id ===
            (int) $user->school_id,
            403,
            'You are not authorized to access this guardian.'
        );
    }
}