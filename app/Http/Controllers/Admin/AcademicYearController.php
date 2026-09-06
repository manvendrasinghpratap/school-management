<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAcademicYearRequest;
use App\Http\Requests\UpdateAcademicYearRequest;
use App\Models\AcademicYears;
use App\Services\AcademicYearService;
use Illuminate\Http\Request;

class AcademicYearController extends Controller
{
    public function __construct(
        protected AcademicYearService $academicYearService
    ) {
    }

    /**
     * Display academic years.
     */
    public function index(Request $request)
    {
        $schoolId = $this->schoolId();

        $query = AcademicYears::query()
            ->where('school_id', $schoolId)
            ->withCount('terms');

        if ($request->filled('search')) {
            $search = trim($request->search);

            $query->where('name', 'like', "%{$search}%");
        }

        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->where('is_active', true);
            }

            if ($request->status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        if ($request->filled('current')) {
            $query->where(
                'is_current',
                $request->current === 'yes'
            );
        }

        $academicYears = $query
            ->orderByDesc('is_current')
            ->orderByDesc('start_date')
            ->paginate(15)
            ->withQueryString();

        return view(
            'admin.academic-years.index',
            compact('academicYears')
        );
    }

    /**
     * Show create form.
     */
    public function create()
    {
        return view('admin.academic-years.create');
    }

    /**
     * Store academic year.
     */
    public function store(StoreAcademicYearRequest $request)
    {
        $academicYear = $this->academicYearService->create(
            $request->validated()
        );

        return redirect()
            ->route(
                'admin.academic-years.show',
                $academicYear
            )
            ->with(
                'success',
                'Academic year created successfully.'
            );
    }

    /**
     * Display academic year details.
     */
    public function show(AcademicYears $academicYear)
    {
        $this->ensureSameSchool($academicYear);

        $academicYear->load([
            'school',
            'terms',
        ]);

        return view(
            'admin.academic-years.show',
            compact('academicYear')
        );
    }

    /**
     * Show edit form.
     */
    public function edit(AcademicYears $academicYear)
    {
        $this->ensureSameSchool($academicYear);

        return view(
            'admin.academic-years.edit',
            compact('academicYear')
        );
    }

    /**
     * Update academic year.
     */
    public function update(
        UpdateAcademicYearRequest $request,
        AcademicYears $academicYear
    ) {
        $this->ensureSameSchool($academicYear);

        $this->academicYearService->update(
            $academicYear,
            $request->validated()
        );

        return redirect()
            ->route(
                'admin.academic-years.show',
                $academicYear
            )
            ->with(
                'success',
                'Academic year updated successfully.'
            );
    }

    /**
     * Soft delete academic year.
     */
    public function destroy(AcademicYears $academicYear)
    {
        $this->ensureSameSchool($academicYear);

        $this->academicYearService->delete(
            $academicYear
        );

        return redirect()
            ->route('admin.academic-years.index')
            ->with(
                'success',
                'Academic year deleted successfully.'
            );
    }

    /**
     * Set academic year as current.
     */
    public function setCurrent(AcademicYears $academicYear)
    {
        $this->ensureSameSchool($academicYear);

        $this->academicYearService->setCurrent(
            $academicYear
        );

        return redirect()
            ->route(
                'admin.academic-years.show',
                $academicYear
            )
            ->with(
                'success',
                'Academic year is now the current academic year.'
            );
    }

    /**
     * Get current user's school.
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
     * Ensure the academic year belongs to the current school.
     */
    protected function ensureSameSchool(
        AcademicYears $academicYear
    ): void {
        if (
            (int) $academicYear->school_id
            !== $this->schoolId()
        ) {
            abort(
                403,
                'You are not authorized to access this academic year.'
            );
        }
    }
}