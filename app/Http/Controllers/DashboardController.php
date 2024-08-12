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
        $query = Permintaan::whereIn('status', ['pending', 'approved by admin'])
            ->orderBy('created_at', 'desc')
            ->limit(5);
        $barangs = Barang::all();
        $kelompoks = Kelompok::with('barangs')->get();

        // Determine the kelompok with the most barangs
        $kelompokWithMostBarangs = $kelompoks->sortByDesc(function ($kelompok) {
            return $kelompok->barangs->count();
        })->first();

        // Mengambil data permintaan berdasarkan peran pengguna
        if ($user->role === 'admin') {
            $permintaans = $query->get();
            return view('admin.index', compact('permintaans', 'barangs', 'kelompoks', 'kelompokWithMostBarangs'));
        } elseif ($user->role === 'pegawai' || $user->role === 'supervisor') {
            $permintaans = $query->where('user_id', $user->id)->get();
            $view = ($user->role === 'pegawai') ? 'pegawai.index' : 'supervisor.index';
            return view($view, compact('permintaans', 'barangs', 'kelompoks', 'kelompokWithMostBarangs'));
        }

        // Redirect jika role tidak dikenali
        return redirect()->route('home');
    }

    public function dashboard()
    {
        $permintaans = Permintaan::where('status', 'pending')
            ->with('pilihan') // Pastikan relasi dimuat
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
        $barangs = Barang::all(); // Added missing variable
        
        return view('admin.index', compact('permintaans', 'barangs'));
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
