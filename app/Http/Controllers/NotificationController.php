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

    // PATCH /notifications/{notification}/read
    public function markRead(Notification $notification)
    {
        $notification->update(['is_read' => true]);
        return $notification;
    }
}
