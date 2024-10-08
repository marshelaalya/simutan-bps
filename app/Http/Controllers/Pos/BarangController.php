<?php

namespace App\Http\Controllers\Pos;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kelompok;
use App\Models\Pemasukan;
use App\Models\Barang;
use App\Models\StokAwalBulan;
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
            ->addColumn('action', function($row){
                return '<div class="table-actions" style="text-align: center; vertical-align: middle;">
                <button class="btn btn-sm add-stock-btn hover:bg-success" 
                    data-bs-toggle="modal" 
                    data-bs-target="#addStockModal" 
                    data-id="' . $row->id . '" 
                    data-nama="' . $row->nama . '" 
                    style="width: 20px; height: 20px; display: flex; align-items: center; justify-content: center; text-decoration: none; color: green; padding: 15px;" 
                    data-tooltip="Tambah Stok Barang">
                <i class="ti ti-circle-plus font-size-20 align-middle"></i>
            </button>
                    <a href="'.route('barang.edit', $row->id).'" class="btn btn-sm hover:bg-warning" style="width: 20px; height: 20px; display: flex; align-items: center; justify-content: center; text-decoration: none; color: #e1a017; padding: 15px;" data-tooltip="Edit Barang">
                        <i class="ti ti-edit font-size-20 align-middle"></i>
                    </a>
                    <a href="'.route('barang.delete', $row->id).'" class="btn btn-sm text-danger hover:bg-danger delete-btn" style="width: 20px; height: 20px; display: flex; align-items: center; justify-content: center; text-decoration: none; color: red; padding: 15px;" data-tooltip="Hapus Barang">
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
    $barangs = Barang::with('kelompok')->get(); // Hapus 'satuan'

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
    $barangs = Barang::with('kelompok')->get(); // Hapus 'satuan'

    return DataTables::of($barangs)
        ->rawColumns([]) // No raw columns
        ->toJson();
}

    public function barangStore(Request $request)
    {

        // Validasi kode barang untuk memastikan tidak ada duplikasi
        $existingBarang = Barang::where('kode', $request->kode_barang)->first();
        
        if ($existingBarang) {
            // Jika kode barang sudah ada, kembalikan dengan pesan error
            $notification = array(
                'message' => "Kode barang sudah terdaftar. Silakan gunakan kode yang berbeda.",
                'alert-type' => "error"
            );
            return redirect()->back()->with($notification);
        }


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
            $extension = $file->getClientOriginalExtension(); // Mendapatkan ekstensi file asli (jpg, jpeg, png, dll)
            $fileName = 'foto_' . $request->kode_barang . '.' . $extension; // Gunakan ekstensi asli
            $filePath = $file->storeAs('public/backend/assets/images/barang', $fileName); // Simpan file
    
            $filePath = str_replace('public/', '', $filePath); // Update path relatif
            $barang->foto_barang = $filePath; // Simpan path file di database
        } else {
            $barang->foto_barang = null; // Jika tidak ada foto, simpan nilai null
        }
        
        $barang->save();

        // Simpan stok awal bulan
        $stokAwalBulan = new StokAwalBulan();
        $stokAwalBulan->barang_id = $barang->id;
        $stokAwalBulan->qty_awal = $request->qty_item; // Set qty_awal dari qty_item yang dimasukkan
        $stokAwalBulan->tahun = Carbon::now()->year; // Menggunakan tahun saat ini
        $stokAwalBulan->bulan = Carbon::now()->month; // Menggunakan bulan saat ini
        $stokAwalBulan->save();

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
        $kode_barang = $request->kode_barang;
        $satuan = $request->satuan;

        // Ambil data barang sebelum di-update
        $barangLama = Barang::findOrFail($barang_id);
        $qtyLama = $barangLama->qty_item;

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

         // Handle file upload for photo
        if ($request->hasFile('foto')) {
            $foto = $request->file('foto');
            $extension = $foto->getClientOriginalExtension(); // Mendapatkan ekstensi file asli
            $fotoPath = $foto->storeAs('public/backend/assets/images/barang', 'foto_' . $kode_barang . '.' . $extension);
            $fotoPath = str_replace('public/', '', $fotoPath); // Menghapus 'public/' dari path untuk penyimpanan yang benar
        } else {
            $fotoPath = $barangLama->foto_barang; // Jika tidak ada foto baru, gunakan yang lama
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

        // Logika untuk mencatat pemasukan atau pengeluaran
        $qtyBaru = $request->qty_item;

        if ($qtyBaru > $qtyLama) {
            // Tambahkan ke pemasukan
            $selisih = $qtyBaru - $qtyLama;
            DB::table('pemasukans')->insert([
                'barang_id' => $barang_id,
                'qty' => $selisih,
                'tanggal' => Carbon::now(),
                'keterangan' => 'Penyesuaian setelah update barang',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(), 
            ]);
        } elseif ($qtyBaru < $qtyLama) {
            // Tambahkan ke pengeluaran
            $selisih = $qtyLama - $qtyBaru;
            
            DB::table('pengeluarans')->insert([
                'barang_id' => $barang_id,
                'qty' => $selisih,
                'tanggal' => Carbon::now(),
                'permintaan_id' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(), 
            ]);
        }

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

public function barangDelete($id)
{
    $barang = barang::findOrFail($id);
    $barang->delete();

    // Jika request berasal dari AJAX, kembalikan JSON response
    if (request()->ajax()) {
        return response()->json(['message' => 'Data barang berhasil dihapus.']);
    }

    // Jika bukan AJAX, lanjutkan dengan redirect
    $notification = array(
        'message' => 'Barang berhasil dihapus',
        'alert-type' => 'success'
    );

    return redirect()->back()->with($notification);
}

    public function exportToExcel(Request $request)
    {
        // Ambil tanggal dari input form
        $tanggal = $request->input('tanggal');
        
        // Ambil semua data barang
        $barang = Barang::all();
    
        // Format filename sesuai dengan tanggal yang dipilih
        $formattedDate = Carbon::parse($tanggal)->format('d M Y');
        $filename = "BA Stock Opname {$formattedDate}.xlsx";
    
        // Kirim data barang dan tanggal ke BarangExport
        return Excel::download(new BarangExport($barang, $tanggal), $filename);
    }
    

    public function exportPemasukan(Request $request)
    {
        // Dapatkan tanggal dari request
        $startDate = Carbon::parse($request->get('start_date'));
        $endDate = Carbon::parse($request->get('end_date'));
        
        // Format tanggal menjadi nama bulan dan tahun dalam bahasa Indonesia
        $startDateFormatted = $startDate->translatedFormat('j F Y');
        $endDateFormatted = $endDate->translatedFormat('j F Y');
        
        // Tentukan path file Excel yang akan diakses di storage/excel
        $filePath = storage_path('app/excel/Laporan_Rincian_Persediaan.xlsx');
        
        // Pastikan file Excel benar-benar ada
        if (file_exists($filePath)) {
            // Panggil fungsi untuk memproses file Excel
            $exporter = new PemasukanExport();
            $updatedFilePath = $exporter->export($filePath, $startDate, $endDate);
            
            // Periksa apakah file sementara berhasil dibuat dan siap diunduh
            if ($updatedFilePath) {
                // Gunakan format tanggal yang diinginkan dalam nama file
                $filename = "Laporan_Rincian_Persediaan_{$startDateFormatted}_{$endDateFormatted}.xlsx";
                return response()->download($updatedFilePath, $filename)->deleteFileAfterSend(true);
            } else {
                return redirect()->back()->with('error', 'Gagal membuat file untuk diunduh.');
            }
        } else {
            return redirect()->back()->with('error', 'File Excel tidak ditemukan!');
        }
    }
    
}