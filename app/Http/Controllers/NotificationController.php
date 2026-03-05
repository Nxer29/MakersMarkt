<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    // GET /users/{user}/notifications
    public function index(int $userId)
    {
        return Notification::where('user_id', $userId)
            ->orderByDesc('created_at')
            ->paginate(30);
    }

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
        $notification->update(['is_read' => true]);

        if ($request->wantsJson()) return $notification;

        return back();
    }
}
