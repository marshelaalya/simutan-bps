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

class DefaultController extends Controller
{
    public function GetCategory(Request $request)
{
    $kelompok_id = $request->kelompok_id;

    try {
        $allCategory = Barang::where('kelompok_id', $kelompok_id)
                             ->select('id', 'nama', 'qty_item', 'satuan') // Pastikan untuk memilih kolom qty_item
                             ->get();
        return response()->json($allCategory);
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
}

}
