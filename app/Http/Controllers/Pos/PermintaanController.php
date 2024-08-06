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
    
    public function PermintaanView($id)
    {
        // Mengambil data permintaan berdasarkan ID
        $permintaan = Permintaan::findOrFail($id);

        // Mengambil data pilihan terkait dengan permintaan
        $pilihan = Pilihan::where('permintaan_id', $id)->get();

        // Mengirim data ke view
        return view('backend.permintaan.permintaan_view', compact('permintaan', 'pilihan'));
    }

    public function PermintaanApprove($id)
    {
        // Temukan permintaan berdasarkan ID
        $permintaan = Permintaan::find($id);
    
        if (!$permintaan) {
            return redirect()->route('permintaan.all')->with('error', 'Permintaan tidak ditemukan');
        }
    
        // Ambil item yang terkait dengan permintaan ini
        $pilihan = Pilihan::where('permintaan_id', $id)->get();
    
        return view('backend.permintaan.permintaan_approve', compact('permintaan', 'pilihan'));
    }
    
    public function PermintaanUpdateStatus(Request $request, $id)
{
    $permintaan = Permintaan::find($id);

    if (!$permintaan) {
        return redirect()->route('permintaan.all')->with('error', 'Permintaan tidak ditemukan');
    }

    // Update status permintaan
    $permintaan->status = $request->input('status');
    
    // Jika status adalah rejected, simpan alasan
    if ($request->input('status') === 'rejected by admin') {
        $permintaan->ctt_adm = $request->input('reason', ''); // Simpan alasan jika ada
    }

    $permintaan->save();

    return redirect()->route('permintaan.all')->with('success', 'Permintaan berhasil diperbarui');
}



}


