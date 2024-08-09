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
        $query = Permintaan::whereIn('status', ['pending', 'approved by admin'])
        ->orderBy('created_at', 'desc')
        ->limit(5);

        // Mengambil data permintaan berdasarkan peran pengguna
        if ($user->role === 'admin') {
            // Untuk admin, ambil semua permintaan yang pending
            $permintaans = $query->get();
            return view('admin.index', compact('permintaans')); // Tampilan untuk dashboard admin
        } elseif ($user->role === 'pegawai' || $user->role === 'supervisor') {
            // Untuk pegawai dan supervisor, ambil permintaan milik pengguna tersebut
            $permintaans = $query->where('user_id', $user->id)->get();
            $view = ($user->role === 'pegawai') ? 'pegawai.index' : 'supervisor.index';
            return view($view, compact('permintaans')); // Tampilan untuk dashboard pegawai dan supervisor
        }

        // Redirect jika role tidak dikenali
        return redirect()->route('home');
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
