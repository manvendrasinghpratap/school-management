<?php

namespace App\Services;

use App\Models\PortalNotification;
use App\Models\User;

class PortalNotificationService
{
    public function send(User $user, string $title, string $body, ?string $type = null, array $data = []): PortalNotification
    {
        abort_unless($user->school_id, 422, 'User is not linked to a school.');
        return PortalNotification::create([
            'user_id' => $user->id,
            'title' => $title,
            'body' => $body,
            'type' => $type,
            'data' => $data,
        ]);
    }

    public function markRead(User $user, PortalNotification $notification): void
    {
        abort_unless((int) $notification->user_id === (int) $user->id, 404);
        $notification->update(['read_at' => now()]);
    }
}
