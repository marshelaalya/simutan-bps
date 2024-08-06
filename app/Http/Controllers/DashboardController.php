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
        
        if ($user->role === 'admin') {
            return view('admin.index'); // Tampilan untuk dashboard admin
        } elseif ($user->role === 'pegawai') {
            return view('pegawai.index'); // Tampilan untuk dashboard pegawai
        }

        return redirect('/'); // Redirect jika role tidak dikenali
    }

    public function dashboard() {
        $permintaans = Permintaan::all(); // Atau query yang sesuai dengan kebutuhan Anda
        return view('admin.index', compact('permintaans'));
    }
    
    
}
