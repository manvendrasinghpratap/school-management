<?php

namespace App\Services;

use App\Models\SchoolEvent;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EventService
{
    public function create(array $data): SchoolEvent
    {
        $user = Auth::user();

        abort_unless(
            $user && $user->school_id,
            403,
            'No school is assigned to the current user.'
        );

        $eventData = [
            'school_id' => (int) $user->school_id,
            'created_by' => Auth::id(),
            'title' => $data['title'],
            'event_type' => $data['event_type'] ?? null,
            'description' => $data['description'] ?? null,
            'start_datetime' => $data['starts_at'],
            'end_datetime' => $data['ends_at'] ?? null,
            'location' => $data['venue'] ?? null,
            'status' => $data['status'],
            'is_public' => (bool) ($data['is_public'] ?? true),
        ];

        return DB::transaction(function () use ($eventData) {
            return SchoolEvent::create($eventData);
        });
    }

    public function update(
        SchoolEvent $event,
        array $data
    ): SchoolEvent {
        $user = Auth::user();

        abort_unless(
            $user && $user->school_id,
            403,
            'No school is assigned to the current user.'
        );

        abort_unless(
            (int) $event->school_id === (int) $user->school_id,
            403,
            'You are not authorized to update this event.'
        );

        $eventData = [
            'title' => $data['title'],
            'event_type' => $data['event_type'] ?? null,
            'description' => $data['description'] ?? null,
            'start_datetime' => $data['starts_at'],
            'end_datetime' => $data['ends_at'] ?? null,
            'location' => $data['venue'] ?? null,
            'status' => $data['status'],
            'is_public' => (bool) ($data['is_public'] ?? true),
        ];

        DB::transaction(function () use ($event, $eventData) {
            $event->update($eventData);
        });

        return $event->fresh([
            'creator',
            'participants.student',
            'participants.staff',
        ]);
    }
}