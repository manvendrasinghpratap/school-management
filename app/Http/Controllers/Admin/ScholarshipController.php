<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Scholarships;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ScholarshipController extends Controller
{
    public function index(Request $request): View
    {
        $user = Auth::user();
        abort_unless($user?->can('scholarships.view'), 403);

        $query = Scholarships::query()
            ->where('school_id', $user->school_id)
            ->withCount('studentFees')
            ->orderBy('name');

        if ($request->filled('search')) {
            $search = trim((string) $request->input('search'));
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->filled('type') && in_array($request->type, ['percentage', 'fixed'], true)) {
            $query->where('type', $request->type);
        }

        if ($request->filled('status') && in_array($request->status, ['active', 'inactive'], true)) {
            $query->where('is_active', $request->status === 'active');
        }

        $scholarships = $query->paginate(15)->withQueryString();

        return view('admin.scholarships.index', compact('scholarships'));
    }

    public function create(): View
    {
        abort_unless(Auth::user()?->can('scholarships.manage'), 403);

        return view('admin.scholarships.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $user = Auth::user();
        abort_unless($user?->can('scholarships.manage'), 403);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'type' => ['required', 'in:percentage,fixed'],
            'value' => ['required', 'numeric', 'min:0'],
            'description' => ['nullable', 'string', 'max:5000'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $this->validateValue($request, $validated['type'], (float) $validated['value']);

        $name = trim($validated['name']);
        $exists = Scholarships::query()
            ->where('school_id', $user->school_id)
            ->whereRaw('LOWER(name) = ?', [mb_strtolower($name)])
            ->exists();

        if ($exists) {
            return back()->withInput()->withErrors([
                'name' => 'A scholarship with this name already exists.',
            ]);
        }

        Scholarships::create([
            'school_id' => $user->school_id,
            'name' => $name,
            'type' => $validated['type'],
            'value' => round((float) $validated['value'], 2),
            'description' => isset($validated['description']) ? trim($validated['description']) : null,
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()
            ->route('admin.scholarships.index')
            ->with('success', 'Scholarship created successfully.');
    }

    public function show(Scholarships $scholarship): View
    {
        $user = Auth::user();
        abort_unless($user?->can('scholarships.view'), 403);
        $this->authorizeSchool($scholarship);

        $scholarship->loadCount('studentFees');
        $scholarship->load([
            'studentFees' => fn ($q) => $q->with(['student', 'feeStructure'])->latest('id')->limit(20),
        ]);

        return view('admin.scholarships.show', compact('scholarship'));
    }

    public function edit(Scholarships $scholarship): View
    {
        $user = Auth::user();
        abort_unless($user?->can('scholarships.manage'), 403);
        $this->authorizeSchool($scholarship);

        return view('admin.scholarships.edit', compact('scholarship'));
    }

    public function update(Request $request, Scholarships $scholarship): RedirectResponse
    {
        $user = Auth::user();
        abort_unless($user?->can('scholarships.manage'), 403);
        $this->authorizeSchool($scholarship);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'type' => ['required', 'in:percentage,fixed'],
            'value' => ['required', 'numeric', 'min:0'],
            'description' => ['nullable', 'string', 'max:5000'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $this->validateValue($request, $validated['type'], (float) $validated['value']);

        $name = trim($validated['name']);
        $exists = Scholarships::query()
            ->where('school_id', $user->school_id)
            ->whereRaw('LOWER(name) = ?', [mb_strtolower($name)])
            ->where('id', '!=', $scholarship->id)
            ->exists();

        if ($exists) {
            return back()->withInput()->withErrors([
                'name' => 'A scholarship with this name already exists.',
            ]);
        }

        $scholarship->update([
            'name' => $name,
            'type' => $validated['type'],
            'value' => round((float) $validated['value'], 2),
            'description' => isset($validated['description']) ? trim($validated['description']) : null,
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()
            ->route('admin.scholarships.index')
            ->with('success', 'Scholarship updated successfully.');
    }

    public function toggleStatus(Scholarships $scholarship): RedirectResponse
    {
        $user = Auth::user();
        abort_unless($user?->can('scholarships.manage'), 403);
        $this->authorizeSchool($scholarship);

        $scholarship->update(['is_active' => ! $scholarship->is_active]);

        return back()->with(
            'success',
            $scholarship->is_active
                ? 'Scholarship activated successfully.'
                : 'Scholarship deactivated successfully.'
        );
    }

    public function destroy(Scholarships $scholarship): RedirectResponse
    {
        $user = Auth::user();
        abort_unless($user?->can('scholarships.manage'), 403);
        $this->authorizeSchool($scholarship);

        if ($scholarship->studentFees()->withTrashed()->exists()) {
            return back()->with('error', 'This scholarship cannot be deleted because it has been assigned to student fees. Deactivate it instead.');
        }

        $scholarship->delete();

        return redirect()
            ->route('admin.scholarships.index')
            ->with('success', 'Scholarship deleted successfully.');
    }

    private function authorizeSchool(Scholarships $scholarship): void
    {
        abort_unless(
            (int) $scholarship->school_id === (int) Auth::user()->school_id,
            403,
            'You are not authorized to access this scholarship.'
        );
    }

    private function validateValue(Request $request, string $type, float $value): void
    {
        if ($type === 'percentage' && $value > 100) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'value' => 'Percentage scholarship value cannot exceed 100%.',
            ]);
        }

        if ($type === 'fixed' && $value < 0) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'value' => 'Fixed scholarship value cannot be negative.',
            ]);
        }
    }
}
