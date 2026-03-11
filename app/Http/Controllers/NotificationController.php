<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    // API: GET /api/notifications
    public function index(Request $request)
    {
        $notifications = Notification::where('user_id', auth()->id())
            ->orderByDesc('created_at')
            ->paginate(30);

        if ($request->wantsJson()) {
            return $notifications;
        }

        return view('notifications.index', compact('notifications'));
    }

    // Web page
    public function page()
    {
        $notifications = Notification::where('user_id', auth()->id())
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('notifications.index', compact('notifications'));
    }

    // PATCH /notifications/{notification}/read
    public function markRead(Request $request, Notification $notification)
    {
        // Security check: user mag alleen eigen notificatie aanpassen
        if ($notification->user_id !== auth()->id()) {
            abort(403);
        }

        $notification->update(['is_read' => true]);

        if ($request->wantsJson()) {
            return $notification;
        }

        return back();
    }
}
