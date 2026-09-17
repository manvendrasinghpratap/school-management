<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreAnnouncementRequest;
use App\Models\Announcement;
use App\Models\Classes;
use App\Models\Section;
use App\Services\CommunicationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AnnouncementController extends Controller
{
    public function __construct(
        private CommunicationService $service
    ) {
    }

    private function schoolId(): int
    {
        abort_unless(
            auth()->check() && auth()->user()?->school_id,
            403,
            'No school is assigned to the current user.'
        );

        return (int) auth()->user()->school_id;
    }

    private function own(Announcement $announcement): void
    {
        abort_unless(
            (int) $announcement->school_id === $this->schoolId(),
            403,
            'You are not authorized to access this announcement.'
        );
    }

    public function index(): View
    {
        $announcements = Announcement::query()
            ->where('school_id', $this->schoolId())
            ->with([
                'creator',
                'classModel',
                'section',
            ])
            ->latest()
            ->paginate(15);

        return view(
            'admin.announcements.index',
            compact('announcements')
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

        $sections = Section::query()
            ->whereIn('class_id', $classes->pluck('id'))
            ->orderBy('name')
            ->get();

        return view(
            'admin.announcements.create',
            compact('classes', 'sections')
        );
    }

    public function store(
        StoreAnnouncementRequest $request
    ): RedirectResponse {
        $this->service->create($request->validated());

        return redirect()
            ->route('admin.announcements.index')
            ->with(
                'success',
                'Announcement created successfully.'
            );
    }

    public function show(Announcement $announcement): View
    {
        $this->own($announcement);

        $announcement->load([
            'creator',
            'classModel',
            'section',
        ]);

        return view(
            'admin.announcements.show',
            compact('announcement')
        );
    }

    public function edit(Announcement $announcement): View
    {
        $this->own($announcement);

        $schoolId = $this->schoolId();

        $classes = Classes::query()
            ->where('school_id', $schoolId)
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        $sections = Section::query()
            ->whereIn('class_id', $classes->pluck('id'))
            ->orderBy('name')
            ->get();

        return view(
            'admin.announcements.edit',
            compact('announcement', 'classes', 'sections')
        );
    }

    public function update(
        StoreAnnouncementRequest $request,
        Announcement $announcement
    ): RedirectResponse {
        $this->own($announcement);

        $this->service->update(
            $announcement,
            $request->validated()
        );

        return redirect()
            ->route('admin.announcements.index')
            ->with(
                'success',
                'Announcement updated successfully.'
            );
    }

    public function destroy(
        Announcement $announcement
    ): RedirectResponse {
        $this->own($announcement);

        $announcement->delete();

        return redirect()
            ->route('admin.announcements.index')
            ->with(
                'success',
                'Announcement deleted successfully.'
            );
    }
}