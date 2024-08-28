<?php

namespace App\Http\Controllers\Pos;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pilihan;
use App\Models\Satuan;
use App\Models\Barang;
use App\Models\Kelompok;
use App\Models\User;
use App\Models\Notification;
use App\Models\Permintaan;
use App\Models\Pengeluaran;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log; // Import Log Facade

class PilihanController extends Controller
{
    public function BarangAll(){
        $barangs = Barang::latest()->get();
        return view('backend.barang.barang_all', compact('barang'));
    } // End Method

    public function barangAdd(){
        $kelompok = Kelompok::all();
        return view('backend.barang.barang_add', compact('kelompok'));
    } // End Method

    public function barangStore(Request $request){
        Barang::insert([
            'nama' => $request->nama,
            'kelompok_id' => $request->kelompok_id,
            'qty_item' => $request->qty_item,
            'satuan' => $request->satuan,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    
        $notification = array(
            'message' => "Barang berhasil ditambahkan.",
            'alert-type' => "Success"
        );

        return redirect()->route('barang.all')->with($notification);
    }

    public function barangEdit($id){

        $kelompok = Kelompok::all();

        $barang = barang::findOrFail($id);
        return view('backend.barang.barang_edit', compact('barang','kelompok'));
    }

    public function barangUpdate(Request $request){
        $barang_id = $request->id;

        barang::findOrFail($barang_id)->update([
            'nama' => $request->nama,
            'kelompok_id' => $request->kelompok_id,
            'qty_item' => $request->qty_item,
            'satuan' => $request->satuan,
            'updated_at' => Carbon::now(),
        ]);

        $notification = array(
            'message' => 'Barang berhasil di update',
            'alert-type' => 'success'
        );

        return redirect()->route('barang.all')->with($notification);
    }

    public function barangDelete($id){
        barang::findOrFail($id)->delete();

        $notification = array(
            'message' => 'barang Barang berhasil dihapus',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
    }

    public function PilihanAll()
    {
        $pilihans = Pilihan::orderBy('date', 'desc')->get(); // Mengambil semua data Pilihan, diurutkan dari yang terbaru
        return view('backend.pilihan.pilihan_all', compact('pilihans')); // Mengirimkan data $pilihans ke view
    }

    public function PilihanAdd()
    {
        $barang = Barang::all();
        $permintaan = Permintaan::all();
        $kelompok = Kelompok::all();
        return view('backend.pilihan.pilihan_add', compact('barang','permintaan','kelompok'));
    }

    public function PermintaanStore(Request $request)
    {
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
    
        // Kirim notifikasi kepada supervisor dan admin
        $rolesToNotify = ['admin', 'supervisor'];
        $usersToNotify = User::whereIn('role', $rolesToNotify)->get();
    
        foreach ($usersToNotify as $user) {
            $notificationMessage = 'Terdapat permintaan baru dari ' . Auth::user()->name . 
                                   '.';
    
            Notification::create([
                'user_id' => $user->id,
                'permintaan_id' => $permintaan->id,
                'message' => $notificationMessage,
            ]);
        }
    
        // Mengembalikan ID permintaan yang baru dibuat
        return $permintaan->id;
    }
    
    public function PilihanStore(Request $request)
    {
        // Ambil data dari input tersembunyi
        $tableData = $request->input('table_data');
        $date = $request->input('hidden_date');
        $description = $request->input('hidden_description');

        // Log data untuk debugging
        Log::info('Table Data:', ['data' => $tableData]);

        // Pastikan data tidak null
        if (empty($tableData)) {
            $notification = array(
                'message' => 'Data tabel tidak ada',
                'alert-type' => 'error'
            );
            return redirect()->back()->with($notification);
        }

        // Decode data JSON
        $tableData = json_decode($tableData, true);

        $permintaanId = $this->PermintaanStore($request);

        // Proses data
        if (is_array($tableData) && !empty($tableData)) {
            // Validasi permintaan ID
            if (!$permintaanId) {
                $notification = array(
                    'message' => 'Gagal membuat permintaan',
                    'alert-type' => 'error'
                );
                return redirect()->back()->with($notification);
            }

            foreach ($tableData as $index => $item) {
                // Validasi data
                if (isset($item['kelompok_nama'], $item['barang_nama'], $item['qty_req'])) {
                    $pilihan = new Pilihan();
                    $pilihan->permintaan_id = $permintaanId; // Gunakan ID permintaan yang baru dibuat
                    $pilihan->date = $date; // Gunakan date dari input tersembunyi
                    $pilihan->description = $description; // Gunakan description dari input tersembunyi
                    // Ambil ID barang dan kelompok dari nama
                    $barang = Barang::where('nama', $item['barang_nama'])->first();
                    $kelompok = Kelompok::where('nama', $item['kelompok_nama'])->first();
                    if ($barang && $kelompok) {
                        $pilihan->barang_id = $barang->id;
                        // $pilihan->kelompok_id = $kelompok->id;
                        $pilihan->req_qty = (int)filter_var($item['qty_req'], FILTER_SANITIZE_NUMBER_INT);
                        $pilihan->pilihan_no = sprintf('P-%04d', $index); // Atur pilihan_no sesuai dengan kebutuhan
                        $pilihan->created_by = Auth::user()->name;
                        $pilihan->created_at = Carbon::now();
                        $pilihan->updated_at = Carbon::now();
                        $pilihan->save(); // Simpan ke database

                        // Catat pengeluaran
                        $pengeluaran = new Pengeluaran();
                        $pengeluaran->barang_id = $barang->id;
                        $pengeluaran->qty = $pilihan->req_qty;
                        $pengeluaran->tanggal = $date;
                        $pengeluaran->permintaan_id = $permintaanId;
                        $pengeluaran->description = $description;
                        $pengeluaran->save();

                        // Kurangi kuantitas barang
                        $barang->qty_item -= $pilihan->req_qty;
                        $barang->save();
                    }
                }
            }

            // Kirimkan notifikasi sukses dan redirect
            $notification = array(
                'message' => 'Permintaan berhasil ditambahkan',
                'alert-type' => 'success'
            );
            return redirect()->route('permintaan.saya')->with($notification);
        } else {
            // Jika $tableData bukan array atau kosong
            $notification = array(
                'message' => 'Data tabel tidak valid',
                'alert-type' => 'error'
            );
            return redirect()->route('permintaan.saya')->with($notification);
        }
    } 
     
    public function PilihanEdit($id)
    {
        $pilihan = Pilihan::findOrFail($id);
        $barang = Barang::all();
        $kelompok = Kelompok::all();

        return view('backend.pilihan.pilihan_edit', compact('pilihan', 'barang', 'kelompok'));
    }

    public function PilihanUpdate(Request $request, $id)
{
    // Ambil data Pilihan berdasarkan ID
    $pilihan = Pilihan::findOrFail($id);

    // Ambil permintaan_id dari Pilihan
    $permintaan_id = $pilihan->permintaan_id;

    // Ambil data tabel dari permintaan
    $tableData = $request->input('table_data');

    // Hapus semua Pilihan yang ada untuk permintaan_id yang sama
    Pilihan::where('permintaan_id', $permintaan_id)->delete();

    // Validasi dan tambahkan data Pilihan baru
    if (is_array($tableData) && !empty($tableData)) {
        foreach ($tableData as $index => $item) {
            // Validasi data
            if (isset($item['kelompok_nama'], $item['barang_nama'], $item['qty_req'])) {
                // Temukan Barang dan Kelompok berdasarkan nama atau ID
                $barang = Barang::where('nama', $item['barang_nama'])->first();
                $kelompok = Kelompok::where('nama', $item['kelompok_nama'])->first();

                if ($barang && $kelompok) {
                    // Buat entri Pilihan baru
                    $newPilihan = new Pilihan();
                    $newPilihan->permintaan_id = $permintaan_id;
                    $newPilihan->date = $item['date']; // Ambil dari data tabel
                    $newPilihan->description = $item['description']; // Ambil dari data tabel
                    $newPilihan->barang_id = $barang->id;
                    $newPilihan->kelompok_id = $kelompok->id;
                    $newPilihan->req_qty = (int)filter_var($item['qty_req'], FILTER_SANITIZE_NUMBER_INT);
                    $newPilihan->pilihan_no = sprintf('P-%04d', $index + 1); // Atur sesuai kebutuhan
                    $newPilihan->created_by = Auth::user()->name;
                    $newPilihan->save(); // Simpan ke database

                    // Sesuaikan kuantitas barang
                    $barang->qty_item -= (int)filter_var($item['qty_req'], FILTER_SANITIZE_NUMBER_INT);
                    $barang->save();
                }
            }
        }
    } else {
        // Jika data tabel tidak valid
        $notification = array(
            'message' => 'Data tabel tidak valid',
            'alert-type' => 'error'
        );
        return redirect()->back()->with($notification);
    }

    // Notifikasi sukses dan redirect
    $notification = array(
        'message' => 'Data berhasil diperbarui dan kuantitas barang diperbarui',
        'alert-type' => 'success'
    );
    return redirect()->route('permintaan.saya')->with($notification);
}


    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:approved,rejected',
        ]);

        $permintaan = Permintaan::find($id);

        if (!$permintaan) {
            return redirect()->route('permintaan.all')->with('error', 'Permintaan tidak ditemukan');
        }

        // Update status permintaan
        $permintaan->status = $request->input('status');
        $permintaan->save();

        return redirect()->route('permintaan.all')->with('success', 'Permintaan berhasil diperbarui');
    }

    public function getBarangList(Request $request)
    {
        $query = Barang::query();

        if ($request->kelompok_id) {
            $query->where('kelompok_id', $request->kelompok_id);
        }

        if ($request->q) {
            $query->where('nama', 'like', '%' . $request->q . '%');
        }

        $barang = $query->select('id', 'nama', 'qty_item', 'satuan')->get();

        return response()->json($barang);
    }


}