<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AcademicYears;
use App\Models\Classes;
use App\Models\FeeCategory;
use App\Models\FeeStructure;
use App\Models\Terms;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use App\Models\StudentEnrollment;

class FeeStructureController extends Controller
{
    /**
     * Display fee structures.
     */
    public function index(Request $request)
    {
        abort_unless(Auth::user()->can('fee-structures.view'), 403);

        $user = Auth::user();

        $query = FeeStructure::query()
            ->where('school_id', $user->school_id)
            ->with([
                'feeCategory',
                'academicYear',
                'classModel',
                'term',
            ]);

        if ($request->filled('search')) {
            $search = trim($request->search);

            $query->where(function ($q) use ($search) {
                $q->whereHas('feeCategory', function ($categoryQuery) use ($search) {
                    $categoryQuery
                        ->where('school_id', Auth::user()->school_id)
                        ->where(function ($category) use ($search) {
                            $category
                                ->where('name', 'like', "%{$search}%")
                                ->orWhere('code', 'like', "%{$search}%");
                        });
                })
                ->orWhereHas('academicYear', function ($yearQuery) use ($search) {
                    $yearQuery->where('name', 'like', "%{$search}%");
                })
                ->orWhereHas('classModel', function ($classQuery) use ($search) {
                    $classQuery->where('name', 'like', "%{$search}%");
                });
            });
        }

        if ($request->filled('fee_category_id')) {
            $query->where('fee_category_id', $request->fee_category_id);
        }

        if ($request->filled('academic_year_id')) {
            $query->where('academic_year_id', $request->academic_year_id);
        }

        if ($request->filled('class_id')) {
            $query->where('class_id', $request->class_id);
        }

        if ($request->filled('term_id')) {
            $query->where('term_id', $request->term_id);
        }

        if ($request->filled('status')) {
            $query->where(
                'is_active',
                $request->status === 'active' ? 1 : 0
            );
        }

        $feeStructures = $query
            ->orderByDesc('id')
            ->paginate(15)
            ->withQueryString();

        $feeCategories = FeeCategory::query()
            ->where('school_id', $user->school_id)
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        $academicYears = AcademicYears::query()
            ->where('school_id', $user->school_id)
            ->orderByDesc('id')
            ->get();

        $classes = Classes::query()
            ->where('school_id', $user->school_id)
            ->orderBy('name')
            ->get();

        $terms = Terms::query()
            ->where('academic_year_id', $request->academic_year_id)
            ->orderBy('id')
            ->get();

        return view('admin.fee-structures.index', compact(
            'feeStructures',
            'feeCategories',
            'academicYears',
            'classes',
            'terms'
        ));
    }

    /**
     * Show create form.
     */
    public function create()
    {
        abort_unless(Auth::user()->can('fee-structures.manage'), 403);

        $user = Auth::user();

        $feeCategories = FeeCategory::query()
            ->where('school_id', $user->school_id)
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        $academicYears = AcademicYears::query()
            ->where('school_id', $user->school_id)
            ->orderByDesc('id')
            ->get();

        $classes = Classes::query()
            ->where('school_id', $user->school_id)
            ->orderBy('name')
            ->get();

        $terms = Terms::query()
            ->where('academic_year_id', old('academic_year_id'))
            ->orderBy('id')
            ->get();

        return view('admin.fee-structures.create', compact(
            'feeCategories',
            'academicYears',
            'classes',
            'terms'
        ));
    }

    /**
     * Store a fee structure.
     */
    public function store(Request $request)
    {
        abort_unless(Auth::user()->can('fee-structures.manage'), 403);

        $user = Auth::user();

        $validated = $request->validate([
            'fee_category_id' => [
                'required',
                'integer',
                Rule::exists('fee_categories', 'id')
                    ->where(fn ($query) => $query
                        ->where('school_id', $user->school_id)
                        ->where('is_active', true)),
            ],

            'academic_year_id' => [
                'required',
                'integer',
                Rule::exists('academic_years', 'id')
                    ->where(fn ($query) => $query
                        ->where('school_id', $user->school_id)),
            ],

            'class_id' => [
                'nullable',
                'integer',
                Rule::exists('classes', 'id')
                    ->where(fn ($query) => $query
                        ->where('school_id', $user->school_id)),
            ],

            'term_id' => [
                'nullable',
                'integer',
                Rule::exists('terms', 'id'),
            ],

            'amount' => [
                'required',
                'numeric',
                'min:0',
                'max:9999999999.99',
            ],

            'due_date' => [
                'nullable',
                'date',
            ],

            'is_active' => [
                'nullable',
                'boolean',
            ],
        ]);

        /*
         * Verify that the selected term belongs to
         * the selected academic year.
         */
        if (!empty($validated['term_id'])) {
            $termBelongsToYear = Terms::query()
                ->where('id', $validated['term_id'])
                ->where('academic_year_id', $validated['academic_year_id'])
                ->exists();

            if (!$termBelongsToYear) {
                return back()
                    ->withInput()
                    ->withErrors([
                        'term_id' => 'The selected term does not belong to the selected academic year.',
                    ]);
            }
        }

        /*
         * Prevent duplicate fee structures for the same:
         * category + academic year + class + term.
         *
         * NULL values are handled explicitly.
         */
        $duplicateQuery = FeeStructure::query()
            ->where('school_id', $user->school_id)
            ->where('fee_category_id', $validated['fee_category_id'])
            ->where('academic_year_id', $validated['academic_year_id']);

        if (array_key_exists('class_id', $validated) && $validated['class_id'] !== null) {
            $duplicateQuery->where('class_id', $validated['class_id']);
        } else {
            $duplicateQuery->whereNull('class_id');
        }

        if (array_key_exists('term_id', $validated) && $validated['term_id'] !== null) {
            $duplicateQuery->where('term_id', $validated['term_id']);
        } else {
            $duplicateQuery->whereNull('term_id');
        }

        if ($duplicateQuery->exists()) {
            return back()
                ->withInput()
                ->withErrors([
                    'fee_category_id' =>
                        'A fee structure with the same fee category, academic year, class and term already exists.',
                ]);
        }

        $feeStructure = FeeStructure::create([
            'school_id' => $user->school_id,
            'fee_category_id' => $validated['fee_category_id'],
            'academic_year_id' => $validated['academic_year_id'],
            'class_id' => $validated['class_id'] ?? null,
            'term_id' => $validated['term_id'] ?? null,
            'amount' => $validated['amount'],
            'due_date' => $validated['due_date'] ?? null,
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()
            ->route('admin.fee-structures.index')
            ->with('success', 'Fee structure created successfully.');
    }

    /**
     * Display a fee structure.
     */
    public function show(FeeStructure $feeStructure)
    {
        abort_unless(Auth::user()->can('fee-structures.view'), 403);

        abort_unless(
            $feeStructure->school_id === Auth::user()->school_id,
            403
        );

        $feeStructure->load([
            'feeCategory',
            'academicYear',
            'classModel',
            'term',
            'installments',
        ]);

        return view('admin.fee-structures.show', compact('feeStructure'));
    }

    /**
     * Show edit form.
     */
    public function edit(FeeStructure $feeStructure)
    {
        abort_unless(Auth::user()->can('fee-structures.manage'), 403);

        abort_unless(
            $feeStructure->school_id === Auth::user()->school_id,
            403
        );

        $user = Auth::user();

        $feeCategories = FeeCategory::query()
            ->where('school_id', $user->school_id)
            ->orderBy('name')
            ->get();

        $academicYears = AcademicYears::query()
            ->where('school_id', $user->school_id)
            ->orderByDesc('id')
            ->get();

        $classes = Classes::query()
            ->where('school_id', $user->school_id)
            ->orderBy('name')
            ->get();

        $terms = Terms::query()
            ->where('academic_year_id', $feeStructure->academic_year_id)
            ->orderBy('id')
            ->get();

        return view('admin.fee-structures.edit', compact(
            'feeStructure',
            'feeCategories',
            'academicYears',
            'classes',
            'terms'
        ));
    }

    /**
     * Update a fee structure.
     */
    public function update(Request $request, FeeStructure $feeStructure)
    {
        abort_unless(Auth::user()->can('fee-structures.manage'), 403);

        abort_unless(
            $feeStructure->school_id === Auth::user()->school_id,
            403
        );

        $user = Auth::user();

        $validated = $request->validate([
            'fee_category_id' => [
                'required',
                'integer',
                Rule::exists('fee_categories', 'id')
                    ->where(fn ($query) => $query
                        ->where('school_id', $user->school_id)),
            ],

            'academic_year_id' => [
                'required',
                'integer',
                Rule::exists('academic_years', 'id')
                    ->where(fn ($query) => $query
                        ->where('school_id', $user->school_id)),
            ],

            'class_id' => [
                'nullable',
                'integer',
                Rule::exists('classes', 'id')
                    ->where(fn ($query) => $query
                        ->where('school_id', $user->school_id)),
            ],

            'term_id' => [
                'nullable',
                'integer',
                Rule::exists('terms', 'id'),
            ],

            'amount' => [
                'required',
                'numeric',
                'min:0',
                'max:9999999999.99',
            ],

            'due_date' => [
                'nullable',
                'date',
            ],

            'is_active' => [
                'nullable',
                'boolean',
            ],
        ]);

        /*
         * Verify term belongs to academic year.
         */
        if (!empty($validated['term_id'])) {
            $termBelongsToYear = Terms::query()
                ->where('id', $validated['term_id'])
                ->where('academic_year_id', $validated['academic_year_id'])
                ->exists();

            if (!$termBelongsToYear) {
                return back()
                    ->withInput()
                    ->withErrors([
                        'term_id' => 'The selected term does not belong to the selected academic year.',
                    ]);
            }
        }

        /*
         * Prevent duplicate structures, excluding current record.
         */
        $duplicateQuery = FeeStructure::query()
            ->where('school_id', $user->school_id)
            ->where('fee_category_id', $validated['fee_category_id'])
            ->where('academic_year_id', $validated['academic_year_id'])
            ->where('id', '!=', $feeStructure->id);

        if (array_key_exists('class_id', $validated) && $validated['class_id'] !== null) {
            $duplicateQuery->where('class_id', $validated['class_id']);
        } else {
            $duplicateQuery->whereNull('class_id');
        }

        if (array_key_exists('term_id', $validated) && $validated['term_id'] !== null) {
            $duplicateQuery->where('term_id', $validated['term_id']);
        } else {
            $duplicateQuery->whereNull('term_id');
        }

        if ($duplicateQuery->exists()) {
            return back()
                ->withInput()
                ->withErrors([
                    'fee_category_id' =>
                        'A fee structure with the same fee category, academic year, class and term already exists.',
                ]);
        }

        $feeStructure->update([
            'fee_category_id' => $validated['fee_category_id'],
            'academic_year_id' => $validated['academic_year_id'],
            'class_id' => $validated['class_id'] ?? null,
            'term_id' => $validated['term_id'] ?? null,
            'amount' => $validated['amount'],
            'due_date' => $validated['due_date'] ?? null,
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()
            ->route('admin.fee-structures.index')
            ->with('success', 'Fee structure updated successfully.');
    }

    /**
     * Toggle active status.
     */
    public function toggleStatus(FeeStructure $feeStructure)
    {
        abort_unless(Auth::user()->can('fee-structures.manage'), 403);

        abort_unless(
            $feeStructure->school_id === Auth::user()->school_id,
            403
        );

        $feeStructure->update([
            'is_active' => !$feeStructure->is_active,
        ]);

        return back()->with(
            'success',
            'Fee structure status updated successfully.'
        );
    }

    /**
     * Delete a fee structure.
     */
    public function destroy(FeeStructure $feeStructure)
    {
        abort_unless(Auth::user()->can('fee-structures.manage'), 403);

        abort_unless(
            $feeStructure->school_id === Auth::user()->school_id,
            403
        );

        /*
         * Once student fee records exist, the structure must not
         * be deleted because it becomes part of financial history.
         */
        if ($feeStructure->studentFees()->exists()) {
            return back()->with(
                'error',
                'This fee structure cannot be deleted because student fee records already exist. Deactivate it instead.'
            );
        }

        /*
         * Once installments exist, preserve the structure.
         */
        if ($feeStructure->installments()->exists()) {
            return back()->with(
                'error',
                'This fee structure cannot be deleted because installment records already exist. Deactivate it instead.'
            );
        }

        $feeStructure->delete();

        return redirect()
            ->route('admin.fee-structures.index')
            ->with('success', 'Fee structure deleted successfully.');
    }
    /**
 * Return terms belonging to the selected academic year.
 */
public function termsByAcademicYear($academicYearId)
{
    abort_unless(
        Auth::user()->can('fee-structures.view') ||
        Auth::user()->can('fee-structures.manage'),
        403
    );

    $user = Auth::user();

    /*
     * Verify that the academic year belongs
     * to the current user's school.
     */
    $academicYear = AcademicYears::query()
        ->where('id', $academicYearId)
        ->where('school_id', $user->school_id)
        ->where('is_active', true)
        ->firstOrFail();

    /*
     * Terms belong directly to an academic year.
     */
    $terms = Terms::query()
        ->where('school_id', $user->school_id)
        ->where('academic_year_id', $academicYear->id)
        ->where('is_active', true)
        ->orderBy('term_number')
        ->orderBy('id')
        ->get([
            'id',
            'name',
            'term_number',
            'academic_year_id',
        ]);

    return response()->json([
        'success' => true,
        'terms' => $terms,
    ]);
}


/**
 * Return classes mapped to the selected academic year.
 *
 * The current classes table does not contain academic_year_id.
 * Academic-year/class mapping is represented by student_enrollments.
 */
public function classesByAcademicYear($academicYearId)
{
    abort_unless(
        Auth::user()->can('fee-structures.view') ||
        Auth::user()->can('fee-structures.manage'),
        403
    );

    $user = Auth::user();

    /*
     * Verify academic year belongs to this school.
     */
    $academicYear = AcademicYears::query()
        ->where('id', $academicYearId)
        ->where('school_id', $user->school_id)
        ->where('is_active', true)
        ->firstOrFail();

    /*
     * Find classes that are actually mapped to this
     * academic year through student enrollments.
     *
     * We deliberately do not load every class in the school.
     */
    $classIds = StudentEnrollment::query()
        ->where('school_id', $user->school_id)
        ->where('academic_year_id', $academicYear->id)
        ->whereNotNull('class_id')
        ->distinct()
        ->pluck('class_id');

    /*
     * Load only active classes belonging to this school.
     */
    $classes = Classes::query()
        ->where('school_id', $user->school_id)
        ->where('is_active', true)
        ->whereIn('id', $classIds)
        ->orderBy('name')
        ->get([
            'id',
            'name',
            'code',
        ]);

    return response()->json([
        'success' => true,
        'classes' => $classes,
    ]);
}

}