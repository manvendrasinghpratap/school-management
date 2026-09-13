<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Grade;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
class GradingController extends Controller
{
    /**
     * Display the grading scale.
     */
    public function index(Request $request): View
    {
        $user = $request->user();

        $schoolId = $this->schoolId($user);

        $grades = Grade::query()
            ->where('school_id', $schoolId)
            ->orderByDesc('minimum_score')
            ->orderByDesc('maximum_score')
            ->paginate(20)
            ->withQueryString();

        return view('admin.grading.index', compact('grades'));
    }

    /**
     * Show the create grading rule form.
     */
    public function create(Request $request): View
    {
        $schoolId = $this->schoolId($request->user());

        return view('admin.grading.create', compact('schoolId'));
    }

    /**
     * Store a new grading rule.
     */
    public function store(Request $request): RedirectResponse
    {
        $schoolId = $this->schoolId($request->user());

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'code' => [
                'required',
                'string',
                'max:20',
                'unique:grades,code',
            ],
            'minimum_score' => [
                'required',
                'numeric',
                'min:0',
                'max:100',
            ],
            'maximum_score' => [
                'required',
                'numeric',
                'min:0',
                'max:100',
                'gte:minimum_score',
            ],
            'grade_point' => [
                'nullable',
                'numeric',
                'min:0',
                'max:100',
            ],
            'result' => [
                'required',
                Rule::in(['pass', 'fail']),
            ],
            'remark' => ['nullable', 'string', 'max:255'],
        ]);

        $this->ensureNoOverlap(
            $schoolId,
            $validated['minimum_score'],
            $validated['maximum_score']
        );

        Grade::create([
            'school_id' => $schoolId,
            'name' => $validated['name'],
            'code' => $validated['code'],
            'minimum_score' => $validated['minimum_score'],
            'maximum_score' => $validated['maximum_score'],
            'grade_point' => $validated['grade_point'] ?? null,
            'result' => $validated['result'],
            'remark' => $validated['remark'] ?? null,
        ]);

        return redirect()
            ->route('admin.grading.index')
            ->with('success', 'Grade created successfully.');
    }

    /**
     * Display a grading rule.
     */
    public function show(Request $request, Grade $grade): View
    {
        $this->ensureGradeBelongsToSchool($request->user(), $grade);

        return view('admin.grading.show', compact('grade'));
    }

    /**
     * Show the edit grading rule form.
     */
    public function edit(Request $request, Grade $grade): View
    {
        $this->ensureGradeBelongsToSchool($request->user(), $grade);

        return view('admin.grading.edit', compact('grade'));
    }

    /**
     * Update a grading rule.
     */
    public function update(
        Request $request,
        Grade $grade
    ): RedirectResponse {
        $schoolId = $this->schoolId($request->user());

        $this->ensureGradeBelongsToSchool($request->user(), $grade);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'code' => [
                'required',
                'string',
                'max:20',
                Rule::unique('grades', 'code')->ignore($grade->id),
            ],
            'minimum_score' => [
                'required',
                'numeric',
                'min:0',
                'max:100',
            ],
            'maximum_score' => [
                'required',
                'numeric',
                'min:0',
                'max:100',
                'gte:minimum_score',
            ],
            'grade_point' => [
                'nullable',
                'numeric',
                'min:0',
                'max:100',
            ],
            'result' => [
                'required',
                Rule::in(['pass', 'fail']),
            ],
            'remark' => ['nullable', 'string', 'max:255'],
        ]);

        $this->ensureNoOverlap(
            $schoolId,
            $validated['minimum_score'],
            $validated['maximum_score'],
            $grade->id
        );

        $grade->update([
            'name' => $validated['name'],
            'code' => $validated['code'],
            'minimum_score' => $validated['minimum_score'],
            'maximum_score' => $validated['maximum_score'],
            'grade_point' => $validated['grade_point'] ?? null,
            'result' => $validated['result'],
            'remark' => $validated['remark'] ?? null,
        ]);

        return redirect()
            ->route('admin.grading.index')
            ->with('success', 'Grade updated successfully.');
    }

    /**
     * Soft delete a grading rule.
     */
    public function destroy(
        Request $request,
        Grade $grade
    ): RedirectResponse {
        $this->ensureGradeBelongsToSchool($request->user(), $grade);

        $grade->delete();

        return redirect()
            ->route('admin.grading.index')
            ->with('success', 'Grade deleted successfully.');
    }

    /**
     * Resolve the current user's school.
     */
    private function schoolId($user): int
    {
        if (!$user) {
            abort(403, 'Unauthenticated.');
        }

        if (empty($user->school_id)) {
            abort(403, 'No school is assigned to the current user.');
        }

        return (int) $user->school_id;
    }

    /**
     * Ensure a grade belongs to the current user's school.
     */
    private function ensureGradeBelongsToSchool($user, Grade $grade): void
    {
        $schoolId = $this->schoolId($user);

        if ((int) $grade->school_id !== $schoolId) {
            abort(403, 'You are not authorized to access this grade.');
        }
    }

    /**
     * Prevent overlapping score ranges within the same school.
     */
    private function ensureNoOverlap(
    int $schoolId,
    $minimumScore,
    $maximumScore,
    ?int $ignoreId = null
): void {
    $query = Grade::query()
        ->where('school_id', $schoolId)
        ->where(function ($query) use ($minimumScore, $maximumScore) {
            $query
                ->where('minimum_score', '<=', $maximumScore)
                ->where('maximum_score', '>=', $minimumScore);
        });

    if ($ignoreId !== null) {
        $query->where('id', '!=', $ignoreId);
    }

    if ($query->exists()) {
        throw ValidationException::withMessages([
            'minimum_score' => 'The score range overlaps with an existing grading range.',
            'maximum_score' => 'Please choose a score range that does not overlap with another grade.',
        ]);
    }
}

}