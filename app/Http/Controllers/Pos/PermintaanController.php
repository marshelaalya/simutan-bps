<?php

namespace App\Http\Controllers\Pos;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Permintaan;

class PermintaanController extends Controller
{
    public function PermintaanAll(){
        //  $permintaans = Permintaan::all();
         $permintaans = Permintaan::latest()->get();
         return view('backend.permintaan.permintaan_all', compact('permintaans'));
    } // End Method
}
