<?php

namespace App\Services;

use App\Models\Announcement;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CommunicationService
{
    /**
     * Create a new announcement for the authenticated user's school.
     */
    public function create(array $data): Announcement
    {
        $user = Auth::user();

        abort_unless(
            $user && $user->school_id,
            403,
            'No school is assigned to the current user.'
        );

        $announcementData = [
            'school_id'    => (int) $user->school_id,
            'created_by'   => Auth::id(),
            'title'        => $data['title'],
            'body'         => $data['body'],
            'target_type'  => $data['audience_type'],
            'class_id'     => $data['class_id'] ?? null,
            'section_id'   => $data['section_id'] ?? null,
            'published_at' => $data['publish_at'] ?? null,
            'expires_at'   => $data['expires_at'] ?? null,
            'status'       => $data['status'],
            'is_pinned'    => (bool) ($data['is_pinned'] ?? false),
        ];

        return DB::transaction(function () use ($announcementData) {
            return Announcement::create($announcementData);
        });
    }

    /**
     * Update an announcement while preserving school ownership.
     */
    public function update(
        Announcement $announcement,
        array $data
    ): Announcement {
        $user = Auth::user();

        abort_unless(
            $user && $user->school_id,
            403,
            'No school is assigned to the current user.'
        );

        abort_unless(
            (int) $announcement->school_id === (int) $user->school_id,
            403,
            'You are not authorized to update this announcement.'
        );

        $announcementData = [
            'title'        => $data['title'],
            'body'         => $data['body'],
            'target_type'  => $data['audience_type'],
            'class_id'     => $data['class_id'] ?? null,
            'section_id'   => $data['section_id'] ?? null,
            'published_at' => $data['publish_at'] ?? null,
            'expires_at'   => $data['expires_at'] ?? null,
            'status'       => $data['status'],
            'is_pinned'    => (bool) ($data['is_pinned'] ?? false),
        ];

        DB::transaction(function () use ($announcement, $announcementData) {
            $announcement->update($announcementData);
        });

        return $announcement->fresh([
            'creator',
            'classModel',
            'section',
        ]);
    }
}