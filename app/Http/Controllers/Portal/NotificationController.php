<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\PortalNotification;
use App\Services\PortalNotificationService;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function __construct(private PortalNotificationService $service) {}

    public function index(Request $request)
    {
        $items = PortalNotification::where('user_id', $request->user()->id)->latest()->paginate(30);
        return view('portal.notifications.index', compact('items'));
    }

    public function read(Request $request, PortalNotification $notification)
    {
        $this->service->markRead($request->user(), $notification);
        return back()->with('success', 'Notification marked as read.');
    }

    public function readAll(Request $request)
    {
        PortalNotification::where('user_id', $request->user()->id)->whereNull('read_at')->update(['read_at' => now()]);
        return back()->with('success', 'Notifications marked as read.');
    }
}
