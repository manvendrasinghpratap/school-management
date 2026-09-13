<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FeeCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class FeeCategoryController extends Controller
{
    /**
     * Display a listing of fee categories.
     */
    public function index(Request $request): View
    {
        $user = Auth::user();

        abort_unless($user->can('fees.view'), 403);

        $query = FeeCategory::query()
            ->where('school_id', $user->school_id)
            ->withCount('feeStructures')
            ->orderBy('name');

        if ($request->filled('search')) {
            $search = trim($request->search);

            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where(
                'is_active',
                $request->status === 'active'
            );
        }

        $categories = $query
            ->paginate(15)
            ->withQueryString();

        return view('admin.fee-categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new fee category.
     */
    public function create(): View
    {
        abort_unless(Auth::user()->can('fees.manage'), 403);

        return view('admin.fee-categories.create');
    }

    /**
     * Store a newly created fee category.
     */
    public function store(Request $request): RedirectResponse
    {
        $user = Auth::user();

        abort_unless($user->can('fees.manage'), 403);

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],
            'code' => [
                'nullable',
                'string',
                'max:100',
            ],
            'description' => [
                'nullable',
                'string',
                'max:5000',
            ],
            'is_active' => [
                'nullable',
                'boolean',
            ],
        ]);

        $nameExists = FeeCategory::query()
            ->where('school_id', $user->school_id)
            ->whereRaw('LOWER(name) = ?', [mb_strtolower(trim($validated['name']))])
            ->exists();

        if ($nameExists) {
            return back()
                ->withInput()
                ->withErrors([
                    'name' => 'A fee category with this name already exists.',
                ]);
        }

        if (!empty($validated['code'])) {
            $code = trim($validated['code']);

            $codeExists = FeeCategory::query()
                ->where('school_id', $user->school_id)
                ->where('code', $code)
                ->exists();

            if ($codeExists) {
                return back()
                    ->withInput()
                    ->withErrors([
                        'code' => 'A fee category with this code already exists.',
                    ]);
            }

            $validated['code'] = $code;
        }

        $validated['name'] = trim($validated['name']);
        $validated['school_id'] = $user->school_id;
        $validated['is_active'] = $request->boolean('is_active', true);

        FeeCategory::create($validated);

        return redirect()
            ->route('admin.fee-categories.index')
            ->with('success', 'Fee category created successfully.');
    }

    /**
     * Display the specified fee category.
     */
    public function show(FeeCategory $feeCategory): View
    {
        $user = Auth::user();

        abort_unless($user->can('fees.view'), 403);

        abort_unless(
            (int) $feeCategory->school_id === (int) $user->school_id,
            403,
            'You are not authorized to access this fee category.'
        );

        $feeCategory->load([
            'feeStructures.academicYear',
            'feeStructures.classModel',
            'feeStructures.term',
        ]);

        return view('admin.fee-categories.show', compact('feeCategory'));
    }

    /**
     * Show the form for editing the specified fee category.
     */
    public function edit(FeeCategory $feeCategory): View
    {
        $user = Auth::user();

        abort_unless($user->can('fees.manage'), 403);

        abort_unless(
            (int) $feeCategory->school_id === (int) $user->school_id,
            403,
            'You are not authorized to edit this fee category.'
        );

        return view('admin.fee-categories.edit', compact('feeCategory'));
    }

    /**
     * Update the specified fee category.
     */
    public function update(
        Request $request,
        FeeCategory $feeCategory
    ): RedirectResponse {
        $user = Auth::user();

        abort_unless($user->can('fees.manage'), 403);

        abort_unless(
            (int) $feeCategory->school_id === (int) $user->school_id,
            403,
            'You are not authorized to update this fee category.'
        );

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],
            'code' => [
                'nullable',
                'string',
                'max:100',
            ],
            'description' => [
                'nullable',
                'string',
                'max:5000',
            ],
            'is_active' => [
                'nullable',
                'boolean',
            ],
        ]);

        $nameExists = FeeCategory::query()
            ->where('school_id', $user->school_id)
            ->whereRaw('LOWER(name) = ?', [mb_strtolower(trim($validated['name']))])
            ->where('id', '!=', $feeCategory->id)
            ->exists();

        if ($nameExists) {
            return back()
                ->withInput()
                ->withErrors([
                    'name' => 'A fee category with this name already exists.',
                ]);
        }

        if (!empty($validated['code'])) {
            $code = trim($validated['code']);

            $codeExists = FeeCategory::query()
                ->where('school_id', $user->school_id)
                ->where('code', $code)
                ->where('id', '!=', $feeCategory->id)
                ->exists();

            if ($codeExists) {
                return back()
                    ->withInput()
                    ->withErrors([
                        'code' => 'A fee category with this code already exists.',
                    ]);
            }

            $validated['code'] = $code;
        } else {
            $validated['code'] = null;
        }

        $validated['name'] = trim($validated['name']);
        $validated['is_active'] = $request->boolean('is_active');

        $feeCategory->update($validated);

        return redirect()
            ->route('admin.fee-categories.index')
            ->with('success', 'Fee category updated successfully.');
    }

    /**
     * Toggle active/inactive status.
     */
    public function toggleStatus(FeeCategory $feeCategory): RedirectResponse
    {
        $user = Auth::user();

        abort_unless($user->can('fees.manage'), 403);

        abort_unless(
            (int) $feeCategory->school_id === (int) $user->school_id,
            403,
            'You are not authorized to modify this fee category.'
        );

        $feeCategory->update([
            'is_active' => !$feeCategory->is_active,
        ]);

        return redirect()
            ->route('admin.fee-categories.index')
            ->with(
                'success',
                'Fee category ' .
                ($feeCategory->is_active ? 'activated' : 'deactivated') .
                ' successfully.'
            );
    }

    /**
     * Remove the specified fee category.
     */
    public function destroy(FeeCategory $feeCategory): RedirectResponse
    {
        $user = Auth::user();

        abort_unless($user->can('fees.manage'), 403);

        abort_unless(
            (int) $feeCategory->school_id === (int) $user->school_id,
            403,
            'You are not authorized to delete this fee category.'
        );

        if ($feeCategory->feeStructures()->exists()) {
            return redirect()
                ->route('admin.fee-categories.index')
                ->with(
                    'error',
                    'This fee category cannot be deleted because it is already used by one or more fee structures.'
                );
        }

        if ($feeCategory->invoiceItems()->exists()) {
            return redirect()
                ->route('admin.fee-categories.index')
                ->with(
                    'error',
                    'This fee category cannot be deleted because it is already used by invoice items.'
                );
        }

        $feeCategory->delete();

        return redirect()
            ->route('admin.fee-categories.index')
            ->with('success', 'Fee category deleted successfully.');
    }
}