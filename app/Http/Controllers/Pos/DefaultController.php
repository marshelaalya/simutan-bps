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
    public function GetCategory(Request $request){
        $kelompok_id = $request->kelompok_id;
        // dd($barang_id);
        $allCategory = Barang::with(['kelompok'])->select('barang_id')->where('kelompok_id', $kelompok_id)->GroupBy('barang_id')->get();
        dd($allCategory);
        return response()->json($allCategory);
    } // End Method
}
