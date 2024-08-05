<?php

namespace App\Http\Controllers\Pos;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Permintaan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\Pilihan;

class PermintaanController extends Controller
{
    public function PermintaanAll(){
        //  $permintaans = Permintaan::all();
         $permintaans = Permintaan::latest()->get();
         return view('backend.permintaan.permintaan_all', compact('permintaans'));
    } // End Method

    public function PermintaanAdd(){
         return view('backend.permintaan.permintaan_add');
    } // End Method
   
    public function PermintaanStore(Request $request){
        // Ambil data terakhir dari tabel untuk menentukan digit pertama
        $lastPermintaan = Permintaan::orderBy('id', 'desc')->first();
        $lastNoPermintaan = $lastPermintaan ? $lastPermintaan->no_permintaan : null;
    
        // Tentukan digit pertama
        $digitPertama = '0000';
        if ($lastNoPermintaan) {
            $lastDigit = (int) substr($lastNoPermintaan, 2, 4);
            $digitPertama = str_pad($lastDigit + 1, 4, '0', STR_PAD_LEFT);
        }
    
        // Format untuk bulan dan tahun
        $bulan = Carbon::now()->format('m');
        $tahun = Carbon::now()->format('Y');
    
        // Format nomor permintaan
        $noPermintaan = "B-{$digitPertama}/31751/PL.711/{$bulan}/{$tahun}";
    
        // Simpan data permintaan
        $permintaan = Permintaan::create([
            'user_id' => Auth::user()->id, // ID user yang sedang login
            'no_permintaan' => $noPermintaan,
            'tgl_request' => Carbon::now()->format('Y-m-d'),
            'status' => 'pending', // Status default
            'ctt_adm' => null,
            'ctt_spv' => null, 
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    
        // Mengembalikan ID permintaan yang baru dibuat
        return $permintaan->id;
    }

    public function ViewPermintaan()
    {
        // Ambil semua permintaan dengan relasi pilihan
        $permintaans = Permintaan::with('pilihan')->get();

        return view('your-view', ['permintaans' => $permintaans]);
    }
    
}
