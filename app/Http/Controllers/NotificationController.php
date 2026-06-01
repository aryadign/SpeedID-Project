<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function unread(Request $request)
    {
        $user = $request->user();
        if (!$user) {
            return response()->json(['count' => 0, 'notifications' => []]);
        }

        $notifications = $user->unreadNotifications()->latest()->take(5)->get();

        return response()->json([
            'count' => $user->unreadNotifications()->count(),
            'notifications' => $notifications->map(fn ($n) => [
                'id' => $n->id,
                'title' => $n->data['title'] ?? 'Notifikasi',
                'message' => $n->data['message'] ?? '',
                'url' => $n->data['url'] ?? '#',
                'created_at' => $n->created_at->diffForHumans(),
            ]),
        ]);
    }

    public function markAsRead(Request $request, string $id)
    {
        $request->user()->notifications()->where('id', $id)->update(['read_at' => now()]);
        return response()->json(['success' => true]);
    }

    public function markAllAsRead(Request $request)
    {
        $request->user()->unreadNotifications()->update(['read_at' => now()]);
        return response()->json(['success' => true]);
    }
}
