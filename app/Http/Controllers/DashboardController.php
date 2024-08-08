<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Permintaan;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Mengambil data permintaan berdasarkan peran pengguna
        if ($user->role === 'admin') {
            // Untuk admin, ambil permintaan dengan status 'pending'
            $permintaans = Permintaan::where('status', 'pending')
                                    ->with('pilihan') // Pastikan relasi dimuat
                                    ->orderBy('created_at', 'desc')
                                    ->limit(5)
                                    ->get();
            return view('admin.index', compact('permintaans')); // Tampilan untuk dashboard admin
        } elseif ($user->role === 'pegawai') {
            // Untuk pegawai, ambil permintaan milik pengguna tersebut
            $permintaans = Permintaan::where('user_id', $user->id)
                                    ->orderBy('created_at', 'desc')
                                    ->limit(5)
                                    ->get();
            return view('pegawai.index', compact('permintaans')); // Tampilan untuk dashboard pegawai
        }

        return view('admin.index'); // Redirect jika role tidak dikenali
    }

    public function dashboard(){
        $permintaans = Permintaan::where('status', 'pending')
                                ->with('pilihan') // Pastikan relasi dimuat
                                ->orderBy('created_at', 'desc')
                                ->limit(5)
                                ->get();
                                
        return view('admin.index', compact('permintaans'));
    }

    public function PermintaanAll(){
        $permintaans = Permintaan::latest()->get();
        return view('backend.permintaan.permintaan_all', compact('permintaans'));
    }

    
    
}
