<?php

namespace App\Http\Controllers\Pos;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Permintaan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\Pilihan;
use App\Models\Barang;
use App\Models\Kelompok;
use Illuminate\Support\Facades\Log; // Import Log Facade

class PermintaanController extends Controller
{
    public function PermintaanAll(){
        $permintaans = Permintaan::latest()->get();
        return view('backend.permintaan.permintaan_all', compact('permintaans'));
    }

    public function PermintaanAdd(){
        return view('backend.permintaan.permintaan_add');
    }
   
    public function PermintaanStore(Request $request){
        // Ambil data terakhir dari tabel untuk menentukan digit pertama
        $lastPermintaan = Permintaan::orderBy('id', 'desc')->first();
        $lastNoPermintaan = $lastPermintaan ? $lastPermintaan->no_permintaan : null;

        // Tentukan digit pertama
        if ($lastNoPermintaan) {
            // Ambil digit terakhir dari nomor permintaan terakhir
            $lastDigit = (int) substr($lastNoPermintaan, 2, 4);
            $digitPertama = $lastDigit + 1; // Digit berikutnya
        } else {
            $digitPertama = 1; // Mulai dari 1 jika tidak ada nomor permintaan sebelumnya
        }
    
        // Format untuk bulan dan tahun
        $bulan = Carbon::now()->format('m');
        $tahun = Carbon::now()->format('Y');
    
        // Format nomor permintaan
        $noPermintaan = "B-{$digitPertama}/31751/PL.711/{$bulan}/{$tahun}";
    
        // Simpan data permintaan
        $permintaan = Permintaan::create([
            'user_id' => Auth::user()->id,
            'no_permintaan' => $noPermintaan,
            'tgl_request' => Carbon::now()->format('Y-m-d'),
            'status' => 'pending',
            'ctt_adm' => null,
            'ctt_spv' => null, 
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    
        // Mengembalikan ID permintaan yang baru dibuat
        return $permintaan->id;
    }

    public function ViewPermintaan(){
        // Ambil semua permintaan dengan relasi pilihan
        $permintaans = Permintaan::with('pilihan')->get();
        return view('your-view', ['permintaans' => $permintaans]);
    }
    
    public function PermintaanView($id){
        // Mengambil data permintaan berdasarkan ID
        $permintaan = Permintaan::findOrFail($id);

        // Mengambil data pilihan terkait dengan permintaan
        $pilihan = Pilihan::where('permintaan_id', $id)->get();

        // Mengirim data ke view
        return view('backend.permintaan.permintaan_view', compact('permintaan', 'pilihan'));
    }

    public function PermintaanApprove($id){
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

        $user = Auth::user();
        $newStatus = $request->input('status');
        $validStatus = false;

        // Tentukan status valid berdasarkan peran pengguna
        if ($user->role === 'admin') {
            if (in_array($newStatus, ['approved', 'rejected'])) {
                $validStatus = true;
            }
        } elseif ($user->role === 'supervisor') {
            if (in_array($newStatus, ['approved', 'rejected'])) {
                $validStatus = true;
            }
        }

        // Jika status tidak valid, kembalikan dengan pesan error
        if (!$validStatus) {
            return redirect()->route('permintaan.all')->with('error', 'Status tidak valid untuk peran Anda');
        }

        // Update status permintaan dengan penyesuaian berdasarkan peran pengguna
        if ($user->role === 'admin') {
            $permintaan->status = $newStatus . ' by admin';
        } elseif ($user->role === 'supervisor') {
            $permintaan->status = $newStatus . ' by supervisor';
        }

        // Jika status adalah rejected, simpan alasan
        if ($newStatus === 'rejected') {
            if ($user->role === 'admin') {
                $permintaan->ctt_adm = $request->input('reason', ''); // Simpan alasan di ctt_adm untuk admin
            } elseif ($user->role === 'supervisor') {
                $permintaan->ctt_supervisor = $request->input('reason', ''); // Simpan alasan di ctt_supervisor untuk supervisor
            }
        } else {
            // Kosongkan alasan jika status tidak rejected
            if ($user->role === 'admin') {
                $permintaan->ctt_adm = null;
            } elseif ($user->role === 'supervisor') {
                $permintaan->ctt_supervisor = null;
            }
        }

        $permintaan->save();

        return redirect()->route('permintaan.all')->with('success', 'Permintaan berhasil diperbarui');
    }



    public function PermintaanSaya()
    {
        $userId = auth()->user()->id;
    
        // Mengambil permintaan berdasarkan user_id
        $permintaans = Permintaan::where('user_id', $userId)->get();
    
        return view('backend.permintaan.permintaan_saya', compact('permintaans'));
    }


    public function permintaanDelete($id)
    {
        $permintaan = Permintaan::findOrFail($id);
        $permintaan->pilihan()->delete();
        $permintaan->delete();

        $notification = array(
            'message' => 'Permintaan berhasil dibatalkan',
            'alert-type' => 'success'
        );

        // Redirect kembali dengan notifikasi
        return redirect()->back()->with($notification);
    }

    public function PermintaanEdit($id)
    {
        // Ambil semua Pilihan berdasarkan permintaan_id
        $pilihan = Pilihan::where('permintaan_id', $id)->get();
        $barang = Barang::all();
        $kelompok = Kelompok::all();

        // Kirim data Pilihan, Barang, dan Kelompok ke view
        return view('backend.permintaan.permintaan_edit', compact('pilihan', 'barang', 'kelompok', 'id'));
    }


    public function PermintaanUpdate(Request $request)
    {
        $permintaan_id = $request->input('permintaan_id');
        $tableData = $request->input('table_data');

        // Hapus entri lama berdasarkan permintaan_id
        Pilihan::where('permintaan_id', $permintaan_id)->delete();

        $tableData = json_decode($request->input('table_data'), true); // Decode JSON jika perlu

    if (is_array($tableData) && !empty($tableData)) {
        foreach ($tableData as $index => $item) {
            // Validasi data
            if (isset($item['kelompok_nama'], $item['barang_nama'], $item['qty_req'])) {
                // Temukan Barang dan Kelompok berdasarkan nama
                $barang = Barang::where('nama', $item['barang_nama'])->first();
                $kelompok = Kelompok::where('nama', $item['kelompok_nama'])->first();

                if ($barang && $kelompok) {
                    // Ekstrak angka dari qty_req
                    $qty_req_str = $item['qty_req'];
                    $qty_req = filter_var($qty_req_str, FILTER_SANITIZE_NUMBER_INT);
                    
                    // Validasi apakah qty_req adalah angka yang valid
                    if ($qty_req === false || !is_numeric($qty_req)) {
                        return redirect()->route('permintaan.saya')->with([
                            'message' => 'Kuantitas tidak valid',
                            'alert-type' => 'error'
                        ]);
                    }

                    // Buat entri Pilihan baru
                    $newPilihan = new Pilihan();
                    $newPilihan->permintaan_id = $permintaan_id;
                    $newPilihan->date = $item['date'] ?? null;
                    $newPilihan->description = $item['description'] ?? null;
                    $newPilihan->barang_id = $barang->id;
                    $newPilihan->req_qty = (int)$qty_req;
                    $newPilihan->pilihan_no = sprintf('P-%04d', $index + 1); // Atur sesuai kebutuhan
                    $newPilihan->created_by = Auth::user()->name;
                    $newPilihan->save(); // Simpan ke database

                    // Sesuaikan kuantitas barang
                    $barang->qty_item -= (int)$qty_req;
                    $barang->save();
                } else {
                    Log::info('Barang atau Kelompok tidak ditemukan:', ['barang' => $item['barang_nama'], 'kelompok' => $item['kelompok_nama']]);
                }
            } else {
                Log::info('Data item tidak valid:', ['item' => $item]);
            }
        }
    } else {
        // Jika data tabel tidak valid
        Log::info('Data tabel tidak valid:', ['data' => $tableData]);

        return redirect()->route('permintaan.saya')->with([
            'message' => 'Data tabel tidak valid',
            'alert-type' => 'error'
        ]);
    }

            

        // Notifikasi sukses dan redirect
        return redirect()->route('permintaan.saya')->with([
            'message' => 'Data berhasil diperbarui dan kuantitas barang diperbarui',
            'alert-type' => 'success'
        ]);
    }
}
