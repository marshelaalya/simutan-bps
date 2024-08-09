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
        $kelompok_id = $request->kelompok_id;
        $barang_id = $request->barang_id;

        // Validasi jika parameter tidak ada
        if (!$kelompok_id || !$barang_id) {
            return response()->json(['error' => 'Invalid input'], 400);
        }

        // Ambil data satuan dari database berdasarkan kelompok_id dan barang_id
        $satuan = Satuan::where('kelompok_id', $kelompok_id)
                        ->where('barang_id', $barang_id)
                        ->get(['id', 'nama']); // Pastikan kolom yang dibutuhkan diambil

        return response()->json($satuan);
    }
}
