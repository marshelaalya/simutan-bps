<?php

namespace App\Http\Controllers\Pos;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pilihan;
use App\Models\Barang;
use App\Models\Kelompok;
use App\Models\Permintaan;
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

                        // Kurangi kuantitas barang
                        $barang->qty_item -= $pilihan->req_qty;
                        $barang->save();
                    }
                }
            }

            // Kirimkan notifikasi sukses dan redirect
            $notification = array(
                'message' => 'Data berhasil disimpan dan kuantitas barang berhasil diperbarui',
                'alert-type' => 'success'
            );
            return redirect()->back()->with($notification);
        } else {
            // Jika $tableData bukan array atau kosong
            $notification = array(
                'message' => 'Data tabel tidak valid',
                'alert-type' => 'error'
            );
            return redirect()->back()->with($notification);
        }
    }

    public function PilihanEdit($id)
    {
        $pilihan = Pilihan::findOrFail($id);
        $barang = Barang::all();
        $kelompok = Kelompok::all();

        return view('backend.pilihan.pilihan_edit', compact('pilihan', 'barang', 'kelompok'));
    }

    public function PilihanUpdate(Request $request)
{
    $tableData = json_decode($request->input('table_data'), true);
    dd($tableData);
    // Fetch the existing Pilihan
    $pilihan = Pilihan::findOrFail($request->id);

    // Update the Pilihan model with the new data
    $pilihan->date = $request->input('date');
    $pilihan->description = $request->input('description');
    $pilihan->save();

    // Process the table data
    

    if (is_array($tableData) && !empty($tableData)) {
        foreach ($tableData as $index => $item) {
            // Validate data
            if (isset($item['kelompok_nama'], $item['barang_nama'], $item['qty_req'])) {
                // Find the Barang and Kelompok by name
                $barang = Barang::where('nama', $item['barang_nama'])->first();
                $kelompok = Kelompok::where('nama', $item['kelompok_nama'])->first();

                if ($barang && $kelompok) {
                    // Find or create a Pilihan record based on the Barang ID and Permintaan ID
                    $existingPilihan = Pilihan::where('barang_id', $barang->id)
                                              ->where('permintaan_id', $pilihan->id)
                                              ->first();

                    if ($existingPilihan) {
                        // Update existing Pilihan
                        $existingPilihan->req_qty = (int)filter_var($item['qty_req'], FILTER_SANITIZE_NUMBER_INT);
                        $existingPilihan->save();
                    } else {
                        // Create a new Pilihan if not found
                        $newPilihan = new Pilihan();
                        $newPilihan->permintaan_id = $pilihan->id;
                        $newPilihan->date = $pilihan->date;
                        $newPilihan->description = $pilihan->description;
                        $newPilihan->barang_id = $barang->id;
                        $newPilihan->kelompok_id = $kelompok->id;
                        $newPilihan->req_qty = (int)filter_var($item['qty_req'], FILTER_SANITIZE_NUMBER_INT);
                        $newPilihan->pilihan_no = sprintf('P-%04d', $index + 1); // Adjust according to your needs
                        $newPilihan->created_by = Auth::user()->name;
                        $newPilihan->save(); // Save new Pilihan
                    }

                    // Adjust the barang quantity
                    $barang->qty_item -= (int)filter_var($item['qty_req'], FILTER_SANITIZE_NUMBER_INT);
                    $barang->save();
                }
            }
        }
    } else {
        // If table data is invalid
        $notification = array(
            'message' => 'Data tabel tidak valid',
            'alert-type' => 'error'
        );
        return redirect()->back()->with($notification);
    }

    // Success notification and redirect
    $notification = array(
        'message' => 'Data berhasil diperbarui dan kuantitas barang diperbarui',
        'alert-type' => 'success'
    );
    return redirect()->route('permintaan.all')->with($notification);
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


    // public function deleteItem(Request $request)
    // {
    // $id = $request->input('id');

    // // Find the item by ID and delete it
    // $pilihan = Pilihan::findOrFail($id);

    // if ($pilihan) {
    //     $pilihan->delete();
    //     return response()->json(['success' => true]);
    // } else {
    //     return response()->json(['success' => false, 'message' => 'Item not found.']);
    // }
    // }


}