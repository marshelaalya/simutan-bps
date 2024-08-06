<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

    
}
