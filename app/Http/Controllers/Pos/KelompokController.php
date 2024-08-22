<?php

namespace App\Http\Controllers\Pos;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kelompok;
use Auth;
use Illuminate\Support\Carbon;
use Yajra\DataTables\DataTables;




class KelompokController extends Controller
{
    public function KelompokAll(){
        $kelompoks = Kelompok::latest()->get();
        return view('backend.kelompok.kelompok_all', compact('kelompoks'));
    } // End Method

    public function KelompokAdd(){
        return view('backend.kelompok.kelompok_add');
    } // End Method

    public function KelompokStore(Request $request){
        Kelompok::insert([
            'nama' => $request->nama,
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

    public function data()
{
    $kelompoks = Kelompok::all();
    return DataTables::of($kelompoks)
    ->addColumn('action', function ($kelompok) {
        return '<div class="table-actions" style="text-align: center; vertical-align: middle;">
                    <a href="'.route('kelompok.edit', $kelompok->id).'" class="btn btn-sm hover:bg-warning" style="width: 20px; height: 20px; display: flex; align-items: center; justify-content: center; text-decoration: none; color: #e1a017; padding: 15px;" data-tooltip="Edit Permintaan">
                        <i class="ti ti-edit font-size-20 align-middle"></i>
                    </a>
                    <a href="'.route('kelompok.delete', $kelompok->id).'" class="btn btn-sm text-danger hover:bg-danger" style="width: 20px; height: 20px; display: flex; align-items: center; justify-content: center; text-decoration: none; color: red; padding: 15px;" data-tooltip="Hapus Permintaan">
                        <i class="ti ti-trash font-size-20 align-middle text-danger"></i>
                    </a>
                   
                </div>';
    })
    
        ->make(true);
}

}
