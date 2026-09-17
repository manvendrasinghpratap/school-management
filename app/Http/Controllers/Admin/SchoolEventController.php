<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreSchoolEventRequest;
use App\Models\EventParticipant;
use App\Models\SchoolEvent;
use App\Models\Staff;
use App\Models\Student;
use App\Services\EventService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SchoolEventController extends Controller
{
    public function __construct(
        private EventService $service
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

    private function own(SchoolEvent $event): void
    {
        abort_unless(
            (int) $event->school_id === $this->schoolId(),
            403,
            'You are not authorized to access this event.'
        );
    }

    public function index(): View
    {
        $events = SchoolEvent::query()
            ->where('school_id', $this->schoolId())
            ->with('creator')
            ->orderByDesc('start_datetime')
            ->paginate(15);

        return view(
            'admin.events.index',
            compact('events')
        );
    }

    public function create(): View
    {
        return view('admin.events.create');
    }

    public function store(
        StoreSchoolEventRequest $request
    ): RedirectResponse {
        $event = $this->service->create(
            $request->validated()
        );

        return redirect()
            ->route('admin.events.show', $event)
            ->with('success', 'Event created successfully.');
    }

    public function show(SchoolEvent $event): View
    {
        $this->own($event);

        $event->load([
            'creator',
            'participants.student',
            'participants.staff',
        ]);

        return view(
            'admin.events.show',
            compact('event')
        );
    }

    public function edit(SchoolEvent $event): View
    {
        $this->own($event);

        return view(
            'admin.events.edit',
            compact('event')
        );
    }

    public function update(
        StoreSchoolEventRequest $request,
        SchoolEvent $event
    ): RedirectResponse {
        $this->own($event);

        $this->service->update(
            $event,
            $request->validated()
        );

        return redirect()
            ->route('admin.events.show', $event)
            ->with('success', 'Event updated successfully.');
    }

    public function destroy(
        SchoolEvent $event
    ): RedirectResponse {
        $this->own($event);

        $event->delete();

        return redirect()
            ->route('admin.events.index')
            ->with('success', 'Event deleted successfully.');
    }

    public function participants(
        SchoolEvent $event
    ): View {
        $this->own($event);

        $schoolId = $this->schoolId();

        $students = Student::query()
            ->where('school_id', $schoolId)
            ->where('status', 'active')
            ->orderBy('first_name')
            ->orderBy('last_name')
            ->get();

        $staff = Staff::query()
            ->where('school_id', $schoolId)
            ->where('status', 'active')
            ->orderBy('first_name')
            ->orderBy('last_name')
            ->get();

        $event->load([
            'participants.student',
            'participants.staff',
        ]);

        return view(
            'admin.events.participants',
            compact('event', 'students', 'staff')
        );
    }

    public function addParticipant(
        SchoolEvent $event
    ): RedirectResponse {
        $this->own($event);

        $type = request('participant_type');
        $id = (int) request('participant_id');

        abort_unless(
            in_array($type, ['student', 'staff'], true) && $id > 0,
            422,
            'Invalid participant.'
        );

        if ($type === 'student') {
            abort_unless(
                Student::query()
                    ->where('school_id', $this->schoolId())
                    ->whereKey($id)
                    ->exists(),
                403,
                'Invalid student.'
            );

            $studentId = $id;
            $staffId = null;
        } else {
            abort_unless(
                Staff::query()
                    ->where('school_id', $this->schoolId())
                    ->whereKey($id)
                    ->exists(),
                403,
                'Invalid staff.'
            );

            $studentId = null;
            $staffId = $id;
        }

        EventParticipant::firstOrCreate(
            [
                'event_id' => $event->id,
                'participant_type' => $type,
                'student_id' => $studentId,
                'staff_id' => $staffId,
            ],
            [
                'attendance_status' => 'invited',
            ]
        );

        return back()->with(
            'success',
            'Participant added.'
        );
    }

    public function removeParticipant(
        SchoolEvent $event,
        EventParticipant $participant
    ): RedirectResponse {
        $this->own($event);

        abort_unless(
            (int) $participant->event_id === (int) $event->id,
            404
        );

        $participant->delete();

        return back()->with(
            'success',
            'Participant removed.'
        );
    }
}