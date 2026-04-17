<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminNotification;
use Illuminate\Http\Request;

class NotificationsController extends Controller
{
    /*
    |─────────────────────────────────────────────────────────────────────────
    | GET /admin/notifications/feed
    | يُستدعى بالـ AJAX polling كل 30 ثانية من لوحة التحكم
    |─────────────────────────────────────────────────────────────────────────
    */
    public function feed()
    {
        $notifications = AdminNotification::recent()->get();
        $unreadCount   = AdminNotification::unread()->count();

        return response()->json([
            'unread_count'  => $unreadCount,
            'notifications' => $notifications->map(fn($n) => [
                'id'          => $n->id,
                'type'        => $n->type,
                'title'       => $n->title,
                'body'        => $n->body,
                'action_url'  => $n->action_url,
                'icon'        => $n->icon,
                'icon_bg'     => $n->icon_bg,
                'icon_color'  => $n->icon_color,
                'is_read'     => $n->is_read,
                'time_ago'    => $n->created_at->diffForHumans(),
                'created_at'  => $n->created_at->format('H:i'),
            ]),
        ]);
    }

    /*
    |─────────────────────────────────────────────────────────────────────────
    | PATCH /admin/notifications/{id}/read
    | تعليم إشعار واحد كمقروء عند النقر عليه
    |─────────────────────────────────────────────────────────────────────────
    */
    public function markRead(AdminNotification $notification)
    {
        $notification->markRead();

        return response()->json([
            'success'      => true,
            'unread_count' => AdminNotification::unread()->count(),
        ]);
    }

    /*
    |─────────────────────────────────────────────────────────────────────────
    | POST /admin/notifications/mark-all-read
    | تعليم كل الإشعارات كمقروءة
    |─────────────────────────────────────────────────────────────────────────
    */
    public function markAllRead()
    {
        AdminNotification::markAllRead();

        return response()->json([
            'success'      => true,
            'unread_count' => 0,
        ]);
    }
}
