<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Permintaan;
use App\Models\Barang;
use App\Models\Kelompok;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $barangs = Barang::all();
        $kelompoks = Kelompok::with('barangs')->get();
        
        
        // Mengambil data permintaan berdasarkan peran pengguna
        if ($user->role === 'admin') {
            // Untuk admin, ambil permintaan dengan status 'pending'
            $permintaans = Permintaan::where('status', 'pending')
                                    ->with('pilihan') // Pastikan relasi dimuat
                                    ->orderBy('created_at', 'desc')
                                    ->limit(5)
                                    ->get();
            
            return view('admin.index', compact('permintaans','barangs')); // Tampilan untuk dashboard admin
        } elseif ($user->role === 'pegawai') {
            // Untuk pegawai, ambil permintaan milik pengguna tersebut
            $permintaans = Permintaan::where('user_id', $user->id)
                                    ->orderBy('created_at', 'desc')
                                    ->limit(5)
                                    ->get();
            $kelompoks = Kelompok::with('barangs')->get();
            return view('pegawai.index', compact('permintaans','barangs', 'kelompoks')); // Tampilan untuk dashboard pegawai
        }

        return view('admin.index'); // Redirect jika role tidak dikenali
    }

    public function dashboard(){
        $permintaans = Permintaan::where('status', 'pending')
                                ->with('pilihan') // Pastikan relasi dimuat
                                ->orderBy('created_at', 'desc')
                                ->limit(5)
                                ->get();
                                
        return view('admin.index', compact('permintaans', 'barangs'));
    }

    public function PermintaanAll(){
        $permintaans = Permintaan::latest()->get();
        return view('backend.permintaan.permintaan_all', compact('permintaans'));
    }

    public function BarangAll(){
        $barangs = Barang::latest()->get();
        return view('backend.barang.barang_all', compact('barangs'));
    } // End Method

    
    
}
