<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = auth()->user()
            ->notifications()
            ->latest()
            ->paginate(20);

        $unreadCount = auth()->user()->unreadNotificationsCount();

        return view('notifications.index', compact('notifications', 'unreadCount'));
    }

    public function fetch()
    {
        $notifications = auth()->user()
            ->notifications()
            ->with('issue')
            ->where(function($query) {
                $query->where('is_read', false)
                      ->orWhere(function($q) {
                          $q->where('is_read', true)
                            ->where('read_at', '>', now()->subHours(24));
                      });
            })
            ->latest()
            ->limit(10)
            ->get();

        $unreadCount = auth()->user()->unreadNotificationsCount();

        return response()->json([
            'notifications' => $notifications,
            'unread_count' => $unreadCount
        ]);
    }

    public function markAsRead($id)
    {
        $notification = Notification::where('user_id', auth()->id())
            ->findOrFail($id);

        $notification->markAsRead();

        return response()->json([
            'success' => true,
            'message' => 'تم تحديد الإشعار كمقروء'
        ]);
    }

    public function markAllAsRead()
    {
        auth()->user()
            ->unreadNotifications()
            ->update([
                'is_read' => true,
                'read_at' => now()
            ]);

        return response()->json([
            'success' => true,
            'message' => 'تم تحديد جميع الإشعارات كمقروءة'
        ]);
    }

    public function destroy($id)
    {
        $notification = Notification::where('user_id', auth()->id())
            ->findOrFail($id);

        $notification->delete();

        return response()->json([
            'success' => true,
            'message' => 'تم حذف الإشعار'
        ]);
    }
}