<?php

namespace App\Http\Controllers\Pos;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pilihan;
use App\Models\Barang;
use App\Models\Kelompok;
use App\Models\Permintaan;
use Auth;
use Illuminate\Support\Carbon;

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

}
