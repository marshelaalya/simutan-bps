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
use App\Exports\BarangExport;
use App\Exports\PemasukanExport;
use Maatwebsite\Excel\Facades\Excel;

use Illuminate\Support\Facades\DB;
use Exception;

class BarangController extends Controller
{
    public function BarangAll(Request $request) {
        if ($request->ajax()) {
            // Query dasar dengan relasi ke tabel 'kelompok'
            $query = Barang::with('kelompok')
                ->select(['barangs.id', 'barangs.kode', 'barangs.kelompok_id', 'barangs.nama', 'barangs.qty_item', 'barangs.satuan', 'barangs.foto_barang']);
    
            // Filter berdasarkan kelompok barang
            if ($request->has('kelompok_id') && !empty($request->kelompok_id)) {
                $kelompokBarang = $request->kelompok_id;
                $query->where('kelompok_id', $kelompokBarang);
            }
    
            // Eksekusi query dan kembalikan hasilnya dalam format DataTables
            $barangs = $query->latest()->get();
            $kelompoks = Kelompok::all();
    
            return DataTables::of($barangs)
                ->addIndexColumn()
                ->addColumn('kode', function ($row) {
                    return $row->kode;
                })
                ->addColumn('kelompok_barang', function ($row) {
                    return $row->kelompok->nama ?? 'Tidak ada data';
                })
                ->addColumn('nama_barang', function ($row) {
                    return $row->nama;
                })
                ->addColumn('stok', function ($row) {
                    return $row->qty_item;
                })
                ->addColumn('satuan', function ($row) {
                    return $row->satuan ?? 'Tidak ada data';
                })
                // ->addColumn('foto_barang', function ($row) {
                //     // Misalnya, jika Anda ingin menampilkan gambar, bisa seperti ini:
                //     return $row->foto_barang ? '<img src="' . asset('storage/' . $row->foto_barang) . '" alt="Foto Barang" style="width: 50px; height: 50px;">' : 'Tidak ada foto';
                // })
                // ->rawColumns(['foto_barang']) // Pastikan rawColumns digunakan untuk kolom yang mengandung HTML
                ->make(true);
        }
    
        $kelompokFilt = Kelompok::select('id', 'nama')->distinct()->get();
    
        $barangs = Barang::with('kelompok')->latest()->get();
    
        return view('backend.barang.barang_all', compact('kelompokFilt', 'barangs'));
    }

public function BarangAllAct(Request $request)
{
    if ($request->ajax()) {
        $query = Barang::with('kelompok')
            ->select(['barangs.id', 'barangs.kode', 'barangs.kelompok_id', 'barangs.nama', 'barangs.qty_item', 'barangs.satuan', 'barangs.foto_barang']);
        
        if ($request->has('kelompok_id') && !empty($request->kelompok_id)) {
            $query->where('kelompok_id', $request->kelompok_id);
        }
        
        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('kelompok_barang', function ($row) {
                return $row->kelompok->nama ?? 'Tidak ada data';
            })
            ->addColumn('foto_barang', function ($row) {
                return $row->foto_barang ? '<img src="' . asset('storage/' . $row->foto_barang) . '" alt="Foto Barang" style="width: 50px; height: 50px;">' : 'Tidak ada foto';
            })
            // ->addColumn('action', function ($row) {
            //     return '
            //         <div class="table-actions">
            //             <button class="btn bg-success btn-sm add-stock-btn" data-bs-toggle="modal" data-bs-target="#addStockModal" data-id="' . $row->id . '" data-nama="' . $row->nama . '">
            //                 <i class="fas fa-plus" style="color: #397e48"></i>
            //             </button>
            //             <a href="' . route('barang.edit', $row->id) . '" class="btn bg-warning btn-sm">
            //                 <i class="fas fa-edit" style="color: #ca8a04"></i>
            //             </a>
            //             <a href="' . route('barang.delete', $row->id) . '" class="btn bg-danger btn-sm">
            //                 <i class="fas fa-trash-alt text-danger"></i>
            //             </a>
            //         </div>';
            // })
            ->addColumn('action', function($row){
                return '<div class="table-actions" style="text-align: center; vertical-align: middle;">
                <button class="btn btn-sm add-stock-btn hover:bg-success" 
                    data-bs-toggle="modal" 
                    data-bs-target="#addStockModal" 
                    data-id="' . $row->id . '" 
                    data-nama="' . $row->nama . '" 
                    style="width: 20px; height: 20px; display: flex; align-items: center; justify-content: center; text-decoration: none; color: green; padding: 15px;" 
                    data-tooltip="Tambah Stok Barang">
                <i class="ti ti-plus font-size-20 align-middle"></i>
            </button>
                    <a href="'.route('barang.edit', $row->id).'" class="btn btn-sm hover:bg-warning" style="width: 20px; height: 20px; display: flex; align-items: center; justify-content: center; text-decoration: none; color: #e1a017; padding: 15px;" data-tooltip="Edit Barang">
                        <i class="ti ti-edit font-size-20 align-middle"></i>
                    </a>
                    <a href="'.route('barang.delete', $row->id).'" class="btn btn-sm text-danger hover:bg-danger" style="width: 20px; height: 20px; display: flex; align-items: center; justify-content: center; text-decoration: none; color: red; padding: 15px;" data-tooltip="Hapus Barang">
                        <i class="ti ti-trash font-size-20 align-middle text-danger"></i>
                    </a>
                   
                </div>';
            })
            ->rawColumns(['foto_barang', 'action'])
            ->make(true);
    }

    $kelompokFilt = Kelompok::select('id', 'nama')->distinct()->get();
    return view('backend.barang.barang_all', compact('kelompokFilt'));
}

    
    
    

    public function dataForAll()
{
    $barangs = Barang::with('kelompok', 'satuan')->get();

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
        ->toJson();
}

public function dataForIndex()
{
    $barangs = Barang::with('kelompok', 'satuan')->get();

    return DataTables::of($barangs)
        // Do not add 'action' column
        ->rawColumns([]) // No raw columns
        ->toJson();
}


    public function barangStore(Request $request)
    {
        $satuan = $request->satuan;
        $satuanBaru = $request->satuanBaru;

        // Jika pilihan satuan adalah 'lainnya' dan satuanBaru tidak kosong
        if ($satuan === 'lainnya' && !empty($satuanBaru)) {
            // Periksa apakah satuan baru sudah ada di database
            $existingSatuan = Barang::where('satuan', strtolower($satuanBaru))->first();
            
            if ($existingSatuan) {
                // Jika sudah ada, gunakan satuan yang sudah ada
                $satuan = $existingSatuan->satuan;
            } else {
                // Jika belum ada, simpan satuan baru ke dalam tabel barang
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

        // Handle file upload
        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $fileName = 'backend/assets/images/barang/foto_' . $request->kode_barang . '.png'; // Generate file name

            $barang->foto_barang = $fileName; // Save file name in database
        }

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

    public function exportToExcel()
    {
        $barang = Barang::all(); 
        
        $currentYear = Carbon::now()->year;
        $currentMonth = Carbon::now()->format('m');

        $filename = "BA Stock Opname {$currentYear} bulan {$currentMonth}.xlsx";

        return Excel::download(new BarangExport($barang), $filename);
    }

    public function exportPemasukan()
    {
        // Tentukan path file Excel yang akan diakses
        $filePath = realpath(resource_path('excel/Laporan_Rincian_Persediaan.xlsx'));
    
        // Pastikan file Excel benar-benar ada
        if (file_exists($filePath)) {
            // Panggil fungsi untuk memproses file Excel
            $exporter = new PemasukanExport();
            $newFilePath = $exporter->export($filePath);
    
            // Tentukan nama file untuk di-download
            $filename = "Laporan_Rincian_Persediaan_Updated.xlsx";
    
            // Return download file
            return response()->download($newFilePath, $filename)->deleteFileAfterSend(true);
        }
    
        return redirect()->back()->with('error', 'File Excel tidak ditemukan!');
    }    
}