<?php

namespace App\Http\Controllers\Pos;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Permintaan;
use App\Models\Notification;
use App\Models\Barang;

use App\Models\Kelompok;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class NotificationController extends Controller
{
    public function markAllRead()
    {
        $user = Auth::user();
        Notification::where('user_id', $user->id)
                    ->where('is_read', false)
                    ->update(['is_read' => true]);
    
        return response()->json(['status' => 'success']);
    }
    
    public function viewAllNotifications()
    {
        $user = Auth::user();
        $notifications = Notification::where('user_id', $user->id)
                                    ->orderBy('created_at', 'desc')
                                    ->get();
        return view('backend.notification.notification_view', compact('notifications'));
    }

    public function markAsRead($id)
    {
        $notification = Notification::find($id);

        if ($notification) {
            $notification->update(['is_read' => true]); // atau gunakan '1' jika kolom is_read adalah integer
        }

        return redirect()->route('notifications.viewAll')->with('success', 'Notifikasi telah ditandai sebagai dibaca.');
    }

    public function loadMore(Request $request)
    {
        $offset = $request->input('offset', 0);
        $limit = 5;

        $notifications = Notification::where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->skip($offset)
            ->take($limit)
            ->get();

        return response()->json($notifications);
    }
}
