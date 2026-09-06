<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTermRequest;
use App\Http\Requests\UpdateTermRequest;
use App\Models\AcademicYears;
use App\Models\Terms;
use App\Services\TermService;
use Illuminate\Http\Request;

class TermController extends Controller
{
    public function __construct(
        protected TermService $termService
    ) {
    }

    /**
     * Display terms.
     */
    public function index(Request $request)
    {
        $schoolId = $this->schoolId();

        $query = Terms::query()
            ->where('school_id', $schoolId)
            ->with('academicYear');

        /*
         * Search by term name.
         */
        if ($request->filled('search')) {
            $search = trim($request->search);

            $query->where('name', 'like', "%{$search}%");
        }

        /*
         * Filter by academic year.
         */
        if ($request->filled('academic_year_id')) {
            $query->where(
                'academic_year_id',
                $request->academic_year_id
            );
        }

        /*
         * Filter by status.
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
         * Filter current term.
         */
        if ($request->filled('current')) {

            $query->where(
                'is_current',
                $request->current === 'yes'
            );
        }

        $terms = $query
            ->orderBy('academic_year_id')
            ->orderBy('term_number')
            ->paginate(15)
            ->withQueryString();

        /*
         * Academic years for the filter dropdown.
         */
        $academicYears = AcademicYears::query()
            ->where('school_id', $schoolId)
            ->where('is_active', true)
            ->orderByDesc('is_current')
            ->orderByDesc('start_date')
            ->get();

        return view(
            'admin.terms.index',
            compact(
                'terms',
                'academicYears'
            )
        );
    }

    /**
     * Show create form.
     */
    public function create()
    {
        $schoolId = $this->schoolId();

        $academicYears = AcademicYears::query()
            ->where('school_id', $schoolId)
            ->where('is_active', true)
            ->orderByDesc('is_current')
            ->orderByDesc('start_date')
            ->get();

        return view(
            'admin.terms.create',
            compact('academicYears')
        );
    }

    /**
     * Store term.
     */
    public function store(StoreTermRequest $request)
    {
        $term = $this->termService->create(
            $request->validated()
        );

        return redirect()
            ->route(
                'admin.terms.show',
                $term
            )
            ->with(
                'success',
                'Term created successfully.'
            );
    }

    /**
     * Display term details.
     */
    public function show(Terms $term)
    {
        $this->ensureSameSchool($term);

        $term->load([
            'school',
            'academicYear',
        ]);

        return view(
            'admin.terms.show',
            compact('term')
        );
    }

    /**
     * Show edit form.
     */
    public function edit(Terms $term)
    {
        $this->ensureSameSchool($term);

        $schoolId = $this->schoolId();

        $academicYears = AcademicYears::query()
            ->where('school_id', $schoolId)
            ->where('is_active', true)
            ->orderByDesc('is_current')
            ->orderByDesc('start_date')
            ->get();

        return view(
            'admin.terms.edit',
            compact(
                'term',
                'academicYears'
            )
        );
    }

    /**
     * Update term.
     */
    public function update(
        UpdateTermRequest $request,
        Terms $term
    ) {
        $this->ensureSameSchool($term);

        $this->termService->update(
            $term,
            $request->validated()
        );

        return redirect()
            ->route(
                'admin.terms.show',
                $term
            )
            ->with(
                'success',
                'Term updated successfully.'
            );
    }

    /**
     * Soft delete term.
     */
    public function destroy(Terms $term)
    {
        $this->ensureSameSchool($term);

        $this->termService->delete($term);

        return redirect()
            ->route('admin.terms.index')
            ->with(
                'success',
                'Term deleted successfully.'
            );
    }

    /**
     * Set term as current.
     */
    public function setCurrent(Terms $term)
    {
        $this->ensureSameSchool($term);

        $this->termService->setCurrent($term);

        return redirect()
            ->route(
                'admin.terms.show',
                $term
            )
            ->with(
                'success',
                'Term is now the current term.'
            );
    }

    /**
     * Get current user's school ID.
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
     * Ensure term belongs to current school.
     */
    protected function ensureSameSchool(Terms $term): void
    {
        if (
            (int) $term->school_id
            !== $this->schoolId()
        ) {
            abort(
                403,
                'You are not authorized to access this term.'
            );
        }
    }
}