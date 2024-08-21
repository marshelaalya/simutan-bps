<?php

namespace App\Http\Controllers\Pos;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kelompok;
use App\Models\Pemasukan;
use App\Models\Barang;
use Auth;
use Illuminate\Support\Carbon;
use Yajra\DataTables\DataTables;

use Illuminate\Support\Facades\DB;
use Exception;

class BarangController extends Controller
{
    public function BarangAll(){
        $barangs = Barang::latest()->get();
        return view('backend.barang.barang_all', compact('barangs'));
    } // End Method

    public function data()
{
    // Fetch data with eager loading of related models
    $barangs = Barang::with('kelompok', 'satuan')->get();

    // Use DataTables to format the data
    return DataTables::of($barangs)
        ->addColumn('action', function ($barang) {
            return '<div class="table-actions" style="text-align: center; vertical-align: middle;">
                        <a href="'.route('barang.edit', $barang->id).'" class="btn bg-warning btn-sm">
                            <i class="fas fa-edit" style="color: #ca8a04"></i>
                        </a>
                        <a href="'.route('barang.delete', $barang->id).'" class="btn bg-danger btn-sm">
                            <i class="fas fa-trash-alt text-danger"></i>
                        </a>
                    </div>';
        })
        ->rawColumns(['action'])
        ->toJson(); // Ensure the data is returned as JSON
}

public function barangStore(Request $request)
{

    $satuan = $request->satuan;
    $satuanBaru = $request->satuanBaru;

    // Jika pilihan satuan adalah 'lainnya' dan satuanBaru tidak kosong
    if ($satuan === 'lainnya' && !empty($satuanBaru)) {
        // Simpan satuan baru
        // Periksa apakah satuan baru sudah ada di database
        $existingSatuan = Barang::where('satuan', strtolower($satuanBaru))->first();
        
        if ($existingSatuan) {
            // Jika sudah ada, gunakan satuan yang sudah ada
            $satuan = $existingSatuan->satuan;
        } else {
            // Jika belum ada, simpan satuan baru ke dalam tabel barang
            // Jika Anda ingin menyimpan satuan baru dalam tabel barang atau menggunakan langsung
            $satuan = strtolower($satuanBaru);
        }
    }

    // Simpan data barang baru
    $barang = new Barang();
    $barang->nama = $request->nama;
    $barang->kode = $request->kode_barang;
    $barang->kelompok_id = $request->kelompok_id;
    $barang->qty_item = $request->qty_item;
    $barang->satuan = $satuan; // Simpan satuan di kolom satuan
    $barang->created_at = Carbon::now();
    $barang->updated_at = Carbon::now();
    $barang->save();

    // Notifikasi sukses
    $notification = array(
        'message' => "Barang berhasil ditambahkan.",
        'alert-type' => "success"
    );

    return redirect()->route('barang.all')->with($notification);
}


    public function KelompokStore(Request $request){
        Kelompok::insert([
            'nama' => $request->nama,
            'kode' => $request->kode_barang,
            // 'created_by' => Auth::user()->id,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    
        $notification = array(
            'message' => "Kelompok Barang berhasil ditambahkan.",
            'alert-type' => "Success"
        );

        return redirect()->route('kelompok.all')->with($notification);
    }

    public function KelompokEdit($id){
        $kelompok = Kelompok::findOrFail($id);
        return view('backend.kelompok.kelompok_edit', compact('kelompok'));
    }

    public function KelompokUpdate(Request $request){
        $kelompok_id = $request->id;

        Kelompok::findOrFail($kelompok_id)->update([
            'nama'=>$request->nama,
            'updated_at' => Carbon::now()
        ]);

        $notification = array(
            'message' => 'Kelompok Barang berhasil di update',
            'alert-type' => 'success'
        );

        return redirect()->route('kelompok.all')->with($notification);
    }

    public function KelompokDelete($id){
        Kelompok::findOrFail($id)->delete();

        $notification = array(
            'message' => 'Kelompok Barang berhasil dihapus',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
    }

    public function barangAdd(){
        $kelompok = Kelompok::all();
        $satuan = Barang::select('satuan')
        ->distinct()
        ->get();
        
        return view('backend.barang.barang_add', compact('kelompok', 'satuan'));
    } 

    public function barangEdit($id)
    {
        $kelompok = Kelompok::all();
        $satuans = Barang::select('satuan')->distinct()->pluck('satuan'); // Mengambil koleksi string
    
        $barang = Barang::findOrFail($id);
        return view('backend.barang.barang_edit', compact('barang', 'kelompok', 'satuans'));
    }
    
    

    public function barangUpdate(Request $request)
    {
        $validated = $request->validate([
            'kode_barang' => 'required|string|max:255',
            'nama' => 'required|string|max:255',
            'kelompok_id' => 'required|integer|exists:kelompoks,id',
            'qty_item' => 'nullable|integer',
            'satuan' => 'required|string',
            'satuanBaru' => 'nullable|string',
        ]);
    
        $barang_id = $request->id;
        $satuan = $request->satuan;
    
        // Jika satuan adalah 'lainnya', periksa dan tambahkan satuan baru
        if ($satuan === 'lainnya') {
            $satuanBaru = $request->satuanBaru;
    
            if (!empty($satuanBaru)) {
                // Check if the new satuan already exists
                $existingSatuan = Barang::whereRaw('LOWER(satuan) = ?', [strtolower($satuanBaru)])->first();
    
                if ($existingSatuan) {
                    // Use the existing satuan
                    $satuan = $existingSatuan->satuan;
                } else {
                    // Create a new satuan
                    $satuan = $satuanBaru;
                }
            }
        }
    
        // Update the barang record
        Barang::findOrFail($barang_id)->update([
            'kode' => $request->kode_barang,
            'nama' => $request->nama,
            'kelompok_id' => $request->kelompok_id,
            'qty_item' => $request->qty_item,
            'satuan' => $satuan,
            'updated_at' => Carbon::now(),
        ]);
    
        $notification = array(
            'message' => 'Barang berhasil di update',
            'alert-type' => 'success'
        );
    
        return redirect()->route('barang.all')->with($notification);
    }

    
    public function addStock(Request $request)
    {

        try {
            // Temukan barang
            $barang = Barang::findOrFail($request->barang_id);

            // Simpan data pemasukan
            Pemasukan::create([
                'barang_id' => $request->barang_id,
                'qty' => $request->qty,
                'tanggal' => now()->toDateString(),
            ]);

            // Tambah stok
            $barang->qty_item += $request->qty;
            $barang->save();

            return response()->json(['success' => 'Stok berhasil ditambahkan.']);
        } catch (Exception $e) {
            // Log error dan kembalikan pesan error
            // Log::error('Error adding stock:', ['exception' => $e->getMessage()]);
            return response()->json(['error' => 'Terjadi kesalahan saat menambahkan stok.'], 500);
        }
    }   

    public function barangDelete($id){
        barang::findOrFail($id)->delete();

        $notification = array(
            'message' => 'barang Barang berhasil dihapus',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
    }
}
