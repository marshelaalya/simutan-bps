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
        $barangId = $request->input('barang_id');
        
        // Ambil satuan berdasarkan barang_id
        $barang = Barang::find($barangId);

        if ($barang) {
            $satuanId = $barang->satuan_id;
            $satuan = Satuan::where('satuan_id', $satuanId)->get();
            
            return response()->json($satuan);
        }
        
        return response()->json([], 404);
    }

}
