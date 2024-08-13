<?php

namespace App\Http\Controllers\Pos;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Barang;
use App\Models\Satuan;


class DefaultController extends Controller
{
    public function getCategory(Request $request)
    {
        $kelompok_id = $request->kelompok_id;

        try {
            $allCategory = Barang::where('kelompok_id', $kelompok_id)
                                 ->select('id', 'nama', 'qty_item') // Pastikan untuk memilih kolom qty_item
                                 ->get();
            return response()->json($allCategory);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getSatuan(Request $request)
    {
        $barang_id = $request->barang_id;
    
        $barang = Barang::where('id', $barang_id)->first(['satuan_id']);
    
        // Ambil data satuan dari database berdasarkan barang_id
        $satuan = Satuan::where('satuan_id', $barang->satuan_id) // Ubah query ini sesuai relasi atau struktur yang benar
                        ->get(['satuan_id', 'nama']); // Pastikan kolom yang dibutuhkan diambil
    
        return response()->json($satuan);
    }


}
