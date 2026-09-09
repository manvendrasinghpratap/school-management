<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AcademicYears;
use App\Models\Examination;
use App\Models\Terms;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ExaminationController extends Controller
{
    /**
     * Display a listing of examinations.
     */
    public function index(Request $request): View
    {
        abort_unless(
            $request->user()?->can('examinations.view'),
            403
        );

        $schoolId = $this->schoolId();

        $query = Examination::query()
            ->where('school_id', $schoolId)
            ->with([
                'academicYear',
                'term',
            ]);

        if ($request->filled('search')) {
            $search = trim(
                $request->string('search')->toString()
            );

            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('type', 'like', "%{$search}%");
            });
        }

        if ($request->filled('academic_year_id')) {
            $query->where(
                'academic_year_id',
                $request->integer('academic_year_id')
            );
        }

        if ($request->filled('term_id')) {
            $query->where(
                'term_id',
                $request->integer('term_id')
            );
        }

        if ($request->filled('status')) {
            $status = $request->string('status')->toString();

            if (
                in_array(
                    $status,
                    [
                        'draft',
                        'scheduled',
                        'ongoing',
                        'completed',
                        'published',
                    ],
                    true
                )
            ) {
                $query->where('status', $status);
            }
        }

        $examinations = $query
            ->orderByDesc('start_date')
            ->orderByDesc('id')
            ->paginate(15)
            ->withQueryString();

        $academicYears = AcademicYears::query()
            ->orderByDesc('id')
            ->get();

        $terms = Terms::query()
            ->orderBy('id')
            ->get();

        return view(
            'admin.examinations.index',
            compact(
                'examinations',
                'academicYears',
                'terms'
            )
        );
    }

    /**
     * Show the examination creation form.
     */
    public function create(Request $request): View
    {
        abort_unless(
            $request->user()?->can('examinations.create'),
            403
        );

        $academicYears = AcademicYears::query()
            ->orderByDesc('id')
            ->get();

        $terms = Terms::query()
            ->orderBy('id')
            ->get();

        return view(
            'admin.examinations.create',
            compact(
                'academicYears',
                'terms'
            )
        );
    }

    /**
     * Store a newly created examination.
     */
    public function store(Request $request): RedirectResponse
    {
        abort_unless(
            $request->user()?->can('examinations.create'),
            403
        );

        $schoolId = $this->schoolId();

        $validated = $request->validate([
            'academic_year_id' => [
                'required',
                'integer',
                'exists:academic_years,id',
            ],
            'term_id' => [
                'nullable',
                'integer',
                'exists:terms,id',
            ],
            'name' => [
                'required',
                'string',
                'max:255',
            ],
            'type' => [
                'required',
                'in:continuous_assessment,mid_term,final,entrance,other',
            ],
            'start_date' => [
                'nullable',
                'date',
            ],
            'end_date' => [
                'nullable',
                'date',
                'after_or_equal:start_date',
            ],
            'status' => [
                'required',
                'in:draft,scheduled,ongoing,completed,published',
            ],
        ]);

        $academicYearExists = AcademicYears::query()
            ->whereKey($validated['academic_year_id'])
            ->exists();

        abort_unless($academicYearExists, 422);

        if (!empty($validated['term_id'])) {
            $termExists = Terms::query()
                ->whereKey($validated['term_id'])
                ->exists();

            abort_unless($termExists, 422);
        }

        Examination::create([
            'school_id' => $schoolId,
            'academic_year_id' => $validated['academic_year_id'],
            'term_id' => $validated['term_id'] ?? null,
            'name' => $validated['name'],
            'type' => $validated['type'],
            'start_date' => $validated['start_date'] ?? null,
            'end_date' => $validated['end_date'] ?? null,
            'status' => $validated['status'],
        ]);

        return redirect()
            ->route('admin.examinations.index')
            ->with(
                'success',
                'Examination created successfully.'
            );
    }

    /**
     * Display the specified examination.
     */
    public function show(
        Request $request,
        Examination $examination
    ): View {
        abort_unless(
            $request->user()?->can('examinations.view'),
            403
        );

        $this->ensureExaminationBelongsToSchool(
            $examination
        );

        $examination->load([
            'academicYear',
            'term',
            'examSchedules',
            'marks',
            'results',
        ]);

        return view(
            'admin.examinations.show',
            compact('examination')
        );
    }

    /**
     * Show the examination edit form.
     */
    public function edit(
        Request $request,
        Examination $examination
    ): View {
        abort_unless(
            $request->user()?->can('examinations.update'),
            403
        );

        $this->ensureExaminationBelongsToSchool(
            $examination
        );

        $academicYears = AcademicYears::query()
            ->orderByDesc('id')
            ->get();

        $terms = Terms::query()
            ->orderBy('id')
            ->get();

        return view(
            'admin.examinations.edit',
            compact(
                'examination',
                'academicYears',
                'terms'
            )
        );
    }

    /**
     * Update the specified examination.
     */
    public function update(
        Request $request,
        Examination $examination
    ): RedirectResponse {
        abort_unless(
            $request->user()?->can('examinations.update'),
            403
        );

        $this->ensureExaminationBelongsToSchool(
            $examination
        );

        $validated = $request->validate([
            'academic_year_id' => [
                'required',
                'integer',
                'exists:academic_years,id',
            ],
            'term_id' => [
                'nullable',
                'integer',
                'exists:terms,id',
            ],
            'name' => [
                'required',
                'string',
                'max:255',
            ],
            'type' => [
                'required',
                'in:continuous_assessment,mid_term,final,entrance,other',
            ],
            'start_date' => [
                'nullable',
                'date',
            ],
            'end_date' => [
                'nullable',
                'date',
                'after_or_equal:start_date',
            ],
            'status' => [
                'required',
                'in:draft,scheduled,ongoing,completed,published',
            ],
        ]);

        $examination->update([
            'academic_year_id' => $validated['academic_year_id'],
            'term_id' => $validated['term_id'] ?? null,
            'name' => $validated['name'],
            'type' => $validated['type'],
            'start_date' => $validated['start_date'] ?? null,
            'end_date' => $validated['end_date'] ?? null,
            'status' => $validated['status'],
        ]);

        return redirect()
            ->route(
                'admin.examinations.show',
                $examination
            )
            ->with(
                'success',
                'Examination updated successfully.'
            );
    }

    /**
     * Soft-delete the specified examination.
     */
    public function destroy(
        Request $request,
        Examination $examination
    ): RedirectResponse {
        abort_unless(
            $request->user()?->can('examinations.delete'),
            403
        );

        $this->ensureExaminationBelongsToSchool(
            $examination
        );

        $examination->delete();

        return redirect()
            ->route('admin.examinations.index')
            ->with(
                'success',
                'Examination deleted successfully.'
            );
    }

    /**
     * Resolve the current user's school.
     */
    protected function schoolId(): int
    {
        $user = Auth::user();

        abort_unless($user, 403);

        if ($user->hasRole('Super Admin')) {
            abort_unless($user->school_id, 403);

            return (int) $user->school_id;
        }

        abort_unless($user->school_id, 403);

        return (int) $user->school_id;
    }

    /**
     * Ensure an examination belongs to the user's school.
     */
    protected function ensureExaminationBelongsToSchool(
        Examination $examination
    ): void {
        $schoolId = $this->schoolId();

        abort_unless(
            (int) $examination->school_id === $schoolId,
            403
        );
    }
}