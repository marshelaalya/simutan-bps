<?php

namespace App\Http\Controllers\Pos;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kelompok;
use App\Models\Satuan;
use App\Models\Barang;
use Auth;
use Illuminate\Support\Carbon;
use Yajra\DataTables\DataTables;

class BarangController extends Controller
{
    public function BarangAll(){
        $barangs = Barang::latest()->get();
        return view('backend.barang.barang_all', compact('barangs'));
    } // End Method

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
    $validated = $request->validate([
        'nama' => 'required|string|max:255',
        'kode_barang' => 'required|string|max:255',
        'kelompok_id' => 'required|integer|exists:kelompoks,id',
        'qty_item' => 'nullable|integer',
        'satuan_id' => 'required|string',
        'satuanBaru' => 'nullable|string', // Validate satuanBaru
    ]);

    $satuan_id = $request->satuan_id;
    $satuanBaru = $request->satuanBaru; // Get satuanBaru from the request

    // If satuan_id is 'lainnya' and satuanBaru is not empty, add new satuan
    if ($satuan_id === 'lainnya' && !empty($satuanBaru)) {
        // Check if the new satuan already exists
        $existingSatuan = Satuan::whereRaw('LOWER(nama) = ?', [strtolower($satuanBaru)])->first();
        
        if ($existingSatuan) {
            // If it exists, use its ID
            $satuan_id = $existingSatuan->satuan_id;
        } else {
            // If it doesn't exist, create a new satuan
            $satuan = new Satuan();
            $satuan->nama = $satuanBaru;
            $satuan->save();
            
            $satuan_id = $satuan->satuan_id; // Use the newly created satuan's ID
        }
    }

    // Create a new Barang instance and save it
    $barang = new Barang();
    $barang->nama = $request->nama;
    $barang->kode = $request->kode_barang;
    $barang->kelompok_id = $request->kelompok_id;
    $barang->qty_item = $request->qty_item; // Correctly assign qty_item
    $barang->satuan_id = $satuan_id;
    $barang->created_at = Carbon::now();
    $barang->updated_at = Carbon::now();
    $barang->save();

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
        $satuans = Satuan::all();
        return view('backend.barang.barang_add', compact('kelompok', 'satuans'));
    } // End Method

    // public function barangStore(Request $request){
    //     Barang::insert([
    //         'nama' => $request->nama,
    //         'kode' => $request->kode_barang,
    //         'kelompok_id' => $request->kelompok_id,
    //         'qty_item' => $request->qty_item,
    //         'satuan_id' => $request->satuan_id,
    //         'created_at' => Carbon::now(),
    //         'updated_at' => Carbon::now(),
    //     ]);
    
    //     $notification = array(
    //         'message' => "Barang berhasil ditambahkan.",
    //         'alert-type' => "Success"
    //     );

    //     return redirect()->route('barang.all')->with($notification);
    // }

    public function barangEdit($id){

        $kelompok = Kelompok::all();

        $barang = barang::findOrFail($id);
        return view('backend.barang.barang_edit', compact('barang','kelompok'));
    }

    public function barangUpdate(Request $request){
        $barang_id = $request->id;

        barang::findOrFail($barang_id)->update([
            'kode' => $request->kode_barang,
            'nama' => $request->nama,
            'kelompok_id' => $request->kelompok_id,
            'qty_item' => $request->qty_item,
            'satuan_id' => $request->satuan_id,
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

    
}
