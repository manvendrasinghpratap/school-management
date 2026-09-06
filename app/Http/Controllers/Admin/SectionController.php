<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreSectionRequest;
use App\Http\Requests\Admin\UpdateSectionRequest;
use App\Models\Classes;
use App\Models\Section;
use App\Services\SectionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SectionController extends Controller
{
    public function __construct(
        protected SectionService $sectionService
    ) {
    }

    public function index(Request $request): View
    {
        $schoolId = $this->schoolId();

        $sections = Section::query()
            ->whereHas('class', function ($query) use ($schoolId) {
                $query->where('school_id', $schoolId);
            })
            ->with('class')
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->string('search');

                $query->where(function ($query) use ($search) {
                    $query
                        ->where('name', 'like', "%{$search}%")
                        ->orWhere('code', 'like', "%{$search}%");
                });
            })
            ->when(
                $request->filled('class_id'),
                fn ($query) => $query->where('class_id', $request->integer('class_id'))
            )
            ->when(
                $request->filled('status'),
                fn ($query) => $query->where(
                    'is_active',
                    $request->string('status') === 'active'
                )
            )
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $classes = Classes::query()
            ->where('school_id', $schoolId)
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return view(
            'admin.sections.index',
            compact('sections', 'classes')
        );
    }

    public function create(): View
    {
        $schoolId = $this->schoolId();

        $classes = Classes::query()
            ->where('school_id', $schoolId)
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return view(
            'admin.sections.create',
            compact('classes')
        );
    }

    public function store(StoreSectionRequest $request): RedirectResponse
    {
        $section = $this->sectionService->create(
            $request->validated()
        );

        return redirect()
            ->route('admin.sections.show', $section)
            ->with('success', 'Section created successfully.');
    }

    public function show(Section $section): View
    {
        $this->ensureSameSchool($section);

        $section->load('class');

        return view(
            'admin.sections.show',
            compact('section')
        );
    }

    public function edit(Section $section): View
    {
        $this->ensureSameSchool($section);

        $schoolId = $this->schoolId();

        $classes = Classes::query()
            ->where('school_id', $schoolId)
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return view(
            'admin.sections.edit',
            compact('section', 'classes')
        );
    }

    public function update(
        UpdateSectionRequest $request,
        Section $section
    ): RedirectResponse {
        $this->ensureSameSchool($section);

        $section = $this->sectionService->update(
            $section,
            $request->validated()
        );

        return redirect()
            ->route('admin.sections.show', $section)
            ->with('success', 'Section updated successfully.');
    }

    public function destroy(Section $section): RedirectResponse
    {
        $this->ensureSameSchool($section);

        $this->sectionService->delete($section);

        return redirect()
            ->route('admin.sections.index')
            ->with('success', 'Section deleted successfully.');
    }

    protected function schoolId(): int
    {
        $user = auth()->user();

        abort_unless(
            $user,
            403,
            'You must be authenticated.'
        );

        abort_unless(
            $user->school_id,
            403,
            'No school is assigned to the current user.'
        );

        return (int) $user->school_id;
    }

    protected function ensureSameSchool(Section $section): void
    {
        $schoolId = $this->schoolId();

        abort_unless(
            $section->class()
                ->where('school_id', $schoolId)
                ->exists(),
            403,
            'You are not authorized to access this section.'
        );
    }
}