<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Permintaan;
use App\Models\Notification;
use App\Models\Barang;
use App\Models\Kelompok;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $query = Permintaan::whereIn('status', ['pending', 'approved by admin'])
            ->orderBy('created_at', 'desc')
            ->limit(5);
        $barangs = Barang::all();
        $kelompoks = Kelompok::with('barangs')->get();

        $notifications = Notification::where('user_id', $user->id)
                                    ->orderBy('created_at', 'desc')
                                    ->limit(5)
                                    ->get();
        $unreadCount = Notification::where('user_id', $user->id)
                                    ->where('is_read', false)
                                    ->count();
            // Determine the kelompok with the most barangs
        $kelompokWithMostBarangs = $kelompoks->sortByDesc(function ($kelompok) {
            return $kelompok->barangs->count();
        })->first();

        // Mengambil data permintaan berdasarkan peran pengguna
        if ($user->role === 'admin' || $user->role === 'supervisor') {
            $permintaans = $query->get();
            return view('admin.index', compact('permintaans', 'barangs', 'kelompoks', 'kelompokWithMostBarangs'));
        } elseif ($user->role === 'pegawai') {
            $permintaans = $query->where('user_id', $user->id)->get();
            $view = ($user->role === 'pegawai') ? 'pegawai.index' : 'supervisor.index';
            return view($view, compact('permintaans',  'barangs', 'kelompoks', 'notifications', 'unreadCount', 'kelompokWithMostBarangs'));
        }
    
        // Redirect jika role tidak dikenali
        return redirect()->route('home');
    }    



    public function dashboard()
    {
        $user = Auth::user();
        $notifications = Notification::where('user_id', $user->id)
                                    ->orderBy('created_at', 'desc')
                                    ->limit(5)
                                    ->get();
        $unreadCount = Notification::where('user_id', $user->id)
                                ->where('is_read', false)
                                ->count();

        return view('dashboard', compact('notifications', 'unreadCount'));
    }

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
    return view('notifications.index', compact('notifications'));
}



    public function PermintaanAll()
    {
        $permintaans = Permintaan::latest()->get();
        return view('backend.permintaan.permintaan_all', compact('permintaans'));
    }

    public function BarangAll()
    {
        $barangs = Barang::latest()->get();
        return view('backend.barang.barang_all', compact('barangs'));
    }
}
