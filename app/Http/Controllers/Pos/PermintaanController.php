<?php

namespace App\Http\Controllers\Pos;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Permintaan;
use App\Models\Pengeluaran;
use App\Models\User;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\Pilihan;
use App\Models\Barang;
use App\Models\Satuan;
use App\Models\Kelompok;
use Illuminate\Support\Facades\Log; // Import Log Facade
use Dompdf\Dompdf;
use Dompdf\Options;
use Yajra\DataTables\DataTables;

class PermintaanController extends Controller
{
    public function PermintaanAll(Request $request){
        if ($request->ajax()) {
            // Query dasar dengan relasi ke tabel 'pilihan'
            $query = Permintaan::with('pilihan')
                ->select(['permintaans.id', 'permintaans.status', 'permintaans.user_id']);
        
            // Filter berdasarkan status persetujuan admin
            if ($request->has('admin_approval') && !empty($request->admin_approval)) {
                if ($request->admin_approval === 'pending') {
                    $query->where('status', 'pending');
                } elseif ($request->admin_approval === 'approved by admin') {
                    $query->where(function($q) {
                        $q->where('status', 'approved by admin')
                          ->orWhere('status', 'approved by supervisor')
                          ->orWhere(function($query) {
                              $query->where('status', 'rejected by supervisor')
                                    ->whereNull('ctt_adm');
                          });
                    });
                } elseif ($request->admin_approval === 'rejected by admin') {
                    $query->where('status', 'rejected by admin');
                }
            }            
        
            // Filter berdasarkan status persetujuan supervisor
            if ($request->has('supervisor_approval') && !empty($request->supervisor_approval)) {
                if ($request->supervisor_approval === 'pending') {
                    $query->where(function($q) {
                        $q->where('status', 'approved by admin')
                          ->orWhere('status', 'pending');
                    });
                } elseif ($request->supervisor_approval === 'approved by supervisor') {
                    $query->where('status', 'approved by supervisor');
                } elseif ($request->supervisor_approval === 'rejected by supervisor') {
                    $query->where(function($q) {
                        $q->where('status', 'rejected by admin')
                          ->orWhere('status', 'rejected by supervisor');
                    });
                }
            }
        
            // Eksekusi query dan kembalikan hasilnya dalam format DataTables
            $permintaans = $query->latest()->get();
            
        
            return DataTables::of($permintaans)
                ->addIndexColumn()
                ->addColumn('date', function ($row) {
                    return \Carbon\Carbon::parse($row->pilihan->first()->date)->locale('id')->isoFormat('D MMMM YYYY');
                })
                ->addColumn('created_by', function ($row) {
                    return $row->pilihan->first()->created_by ?? 'Tidak ada data';
                })
                ->addColumn('description', function ($row) {
                    return $row->pilihan->first()->description ?? 'Tidak ada data';
                })
                ->addColumn('approval_status', function ($row) {
                    // Status Admin
                    $adminStatus = '';
                    if ($row->status == 'pending') {
                        $adminStatus = '<button class="btn btn-secondary text-gray btn-sm font-size-13" style="border: 1px solid #505D69; color: #6b7280; background-color:#edeef0; pointer-events: none; cursor: not-allowed; margin-bottom: 0.5rem; opacity:0.7; padding: .1rem .25rem;">Admin Pending</button>';                    
                    } elseif (($row->status == 'rejected by admin') || ($row->status == 'rejected by supervisor' && $row->ctt_adm !== NULL)) {
                        $adminStatus = '<button class="btn btn-secondary text-danger btn-sm font-size-13" style="border: 1px solid #F32F53; pointer-events: none; background-color: #feeef1; cursor: not-allowed; margin-bottom: 0.5rem; opacity:0.8; padding: .1rem .25rem;">Admin Rejected</button>';
                    } elseif ($row->status == 'approved by admin' || ($row->status == 'rejected by supervisor' && $row->ctt_adm == NULL) || $row->status == 'approved by supervisor') {
                        $adminStatus = '<button class="btn btn-secondary text-success btn-sm font-size-13" style="border: 1px solid #46cf74; background-color:#f3fbf5; pointer-events: none; cursor: not-allowed; margin-bottom: 0.5rem; opacity:0.8; padding: .1rem .25rem;">Admin Approved</button>';
                    }
        
                    // Status Supervisor
                    $supervisorStatus = '';
                    if ($row->status == 'approved by admin' || $row->status == 'pending') {
                        $supervisorStatus = '<button class="btn btn-secondary text-gray btn-sm font-size-13" style="border: 1px solid #505D69; background-color:#edeef0; color: #6b7280; pointer-events: none; cursor: not-allowed; opacity:0.7; padding: .1rem .25rem;">Supervisor Pending</button>';
                    } elseif ($row->status == 'rejected by supervisor' || $row->status == 'rejected by admin') {
                        $supervisorStatus = '<button class="btn btn-secondary text-danger btn-sm font-size-13" style="border: 1px solid #F32F53; background-color: #feeef1; pointer-events: none; cursor: not-allowed;; opacity:0.8; padding: .1rem .25rem;">Supervisor Rejected</button>';
                    } elseif ($row->status == 'approved by supervisor') {
                        $supervisorStatus = '<button class="btn btn-secondary text-success btn-sm font-size-13" style="border: 1px solid #46cf74; background-color:#f3fbf5; pointer-events: none; cursor: not-allowed;; opacity:0.8; padding: .1rem .25rem;">Supervisor Approved</button>';
                    }
        
                    // Menggabungkan status admin dan supervisor
                    return '<div class="d-flex flex-column align-items-start">' . $adminStatus . $supervisorStatus . '</div>';
                })
                ->addColumn('action', function ($row) {
                    $viewButton = '<a href="'.route('permintaan.view', $row->id).'" class="btn btn-sm me-2 text-primary hover:bg-primary" style="width: 20px; height: 20px; display: flex; align-items: center; justify-content: center; text-decoration: none; color: blue; padding: 15px;" data-tooltip="Lihat Permintaan"><i class="ti ti-eye font-size-20 align-middle"></i></a>';
                    
                    $approveOrPrintButton = $row->status == 'approved by supervisor' ?
                        '<a href="'.route('permintaan.print', $row->id).'" class="btn btn-sm text-danger hover:bg-danger" style="width: 20px; height: 20px; display: flex; align-items: center; justify-content: center; text-decoration: none; color: red; padding: 15px;" data-tooltip="Cetak Permintaan"><i class="ti ti-printer font-size-20 align-middle text-danger"></i></a>' :
                        '<a href="'.route('permintaan.approve', $row->id).'" class="btn btn-sm ' . ($row->status == 'pending' ? 'hover:bg-success' : '') . '" style="width: 20px; height: 20px; display: flex; align-items: center; justify-content: center; text-decoration: none;' . ($row->status == 'pending' ? 'color: green;' : 'color: gray; pointer-events: none; opacity: 0.5;') . ' padding: 15px;" data-tooltip="Setujui Permintaan"><i class="ti ti-clipboard-check font-size-20 align-middle"></i></a>';
        
                    return '<div class="text-center d-flex justify-content-center align-items-center">' . $viewButton . $approveOrPrintButton . '</div>';
                })
                ->rawColumns(['approval_status', 'action'])
                ->make(true);
        }
    
        // Mengambil status approval yang unik
        $statusAppr = Permintaan::select('status')->distinct()->get();
    
        return view('backend.permintaan.permintaan_all', compact('statusAppr'));
    }
    


    public function PermintaanAdd(){
        return view('backend.permintaan.permintaan_add');
    }
   
    public function PermintaanStore(Request $request){
        // Ambil data terakhir dari tabel untuk menentukan digit pertama
        $lastPermintaan = Permintaan::orderBy('id', 'desc')->first();
        $lastNoPermintaan = $lastPermintaan ? $lastPermintaan->no_permintaan : null;

        // Tentukan digit pertama
        if ($lastNoPermintaan) {
            // Ambil digit terakhir dari nomor permintaan terakhir
            $lastDigit = (int) substr($lastNoPermintaan, 2, 4);
            $digitPertama = $lastDigit + 1; // Digit berikutnya
        } else {
            $digitPertama = 1; // Mulai dari 1 jika tidak ada nomor permintaan sebelumnya
        }
    
        // Format untuk bulan dan tahun
        $bulan = Carbon::now()->format('m');
        $tahun = Carbon::now()->format('Y');
    
        // Format nomor permintaan
        $noPermintaan = "B-{$digitPertama}/31751/PL.711/{$bulan}/{$tahun}";
    
        // Simpan data permintaan
        $permintaan = Permintaan::create([
            'user_id' => Auth::user()->id,
            'no_permintaan' => $noPermintaan,
            'tgl_request' => Carbon::now()->format('Y-m-d'),
            'status' => 'pending',
            'ctt_adm' => null,
            'ctt_spv' => null, 
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    
        // Mengembalikan ID permintaan yang baru dibuat
        return $permintaan->id;
    }

    public function ViewPermintaan(){
        // Ambil semua permintaan dengan relasi pilihan
        $permintaans = Permintaan::with('pilihan')->get();
        return view('your-view', ['permintaans' => $permintaans]);
    }
    
    public function PermintaanView($id){
        // Mengambil data permintaan berdasarkan ID
        $permintaan = Permintaan::findOrFail($id);

        // Mengambil data pilihan terkait dengan permintaan
        $pilihan = Pilihan::where('permintaan_id', $id)->get();

        // Mengirim data ke view
        return view('backend.permintaan.permintaan_view', compact('permintaan', 'pilihan'));
    }

    public function PermintaanApprove($id){
        // Temukan permintaan berdasarkan ID
        $permintaan = Permintaan::find($id);
    
        if (!$permintaan) {
            return redirect()->route('permintaan.all')->with('error', 'Permintaan tidak ditemukan');
        }
    
        // Ambil item yang terkait dengan permintaan ini
        $pilihan = Pilihan::where('permintaan_id', $id)->get();
    
        return view('backend.permintaan.permintaan_approve', compact('permintaan', 'pilihan'));
    }
    
    public function PermintaanUpdateStatus(Request $request, $id)
    {
        $permintaan = Permintaan::find($id);
        
        if (!$permintaan) {
            return redirect()->route('permintaan.all')->with('error', 'Permintaan tidak ditemukan');
        }
        
        $user = Auth::user();
        $newStatus = $request->input('status');
        $validStatus = false;
    
        // Validasi status berdasarkan peran pengguna
        if (in_array($user->role, ['admin', 'supervisor']) && in_array($newStatus, ['approved', 'rejected'])) {
            $validStatus = true;
        }
        
        if (!$validStatus) {
            return redirect()->route('permintaan.all')->with('error', 'Status tidak valid untuk peran Anda');
        }
        
        // Update status permintaan dengan penyesuaian berdasarkan peran pengguna
        if ($user->role === 'admin') {
            $permintaan->status = $newStatus === 'rejected' ? 'rejected by admin' : 'approved by admin';
        } elseif ($user->role === 'supervisor') {
            $permintaan->status = $newStatus === 'rejected' ? 'rejected by supervisor' : 'approved by supervisor';
        }
        
        // Simpan alasan jika status adalah rejected
        if ($newStatus === 'rejected') {
            if ($user->role === 'admin') {
                $permintaan->ctt_adm = $request->input('reason', '');
            } elseif ($user->role === 'supervisor') {
                $permintaan->ctt_spv = $request->input('reason', '');
            }
    
            // Mengembalikan kuantitas barang yang sebelumnya dikurangi
            foreach ($permintaan->pilihan as $pilihan) {
                $barang = Barang::find($pilihan->barang_id);
                if ($barang) {
                    $barang->qty_item += $pilihan->req_qty; // Mengembalikan kuantitas
                    $barang->save();

                    // / Hapus entri pengeluaran yang terkait
                    Pengeluaran::where('permintaan_id', $permintaan->id)
                        ->where('barang_id', $barang->id)
                        ->delete();
                }
            }
        } else {
            if ($user->role === 'admin') {
                $permintaan->ctt_adm = null;
            } elseif ($user->role === 'supervisor') {
                $permintaan->ctt_spv = null;
            }
        }
        
        $permintaan->save();
        
        // Kirim notifikasi kepada pengguna yang membuat permintaan
        $requestUser = User::find($permintaan->user_id);
        
        if ($requestUser) {
            $notificationMessage = '';
            
            if ($user->role === 'admin') {
                $notificationMessage = $newStatus === 'rejected' ? 'Permintaan ' . $permintaan->no_permintaan . ' telah ditolak oleh admin.' : 'Permintaan ' . $permintaan->no_permintaan . ' telah disetujui oleh admin.';
            } elseif ($user->role === 'supervisor') {
                $notificationMessage = $newStatus === 'rejected' ? 'Permintaan ' . $permintaan->no_permintaan . ' telah ditolak oleh supervisor.' : 'Permintaan ' . $permintaan->no_permintaan . ' telah disetujui oleh supervisor.';
            }
            
            Notification::create([
                'user_id' => $requestUser->id,
                'permintaan_id' => $permintaan->id,
                'message' => $notificationMessage,
            ]);
        }
    
        return redirect()->route('permintaan.all')->with('success', 'Permintaan berhasil diperbarui');
    }
    public function PermintaanSaya(Request $request)
{
    $userId = auth()->user()->id;

    if ($request->ajax()) {
        // Query dasar dengan filter user_id
        $query = Permintaan::with('pilihan')
            ->where('user_id', $userId)
            ->select(['permintaans.id', 'permintaans.status', 'permintaans.user_id']);
        
        // Filter berdasarkan status persetujuan admin
        if ($request->has('admin_approval') && !empty($request->admin_approval)) {
            if ($request->admin_approval === 'pending') {
                $query->where('status', 'pending');
            } elseif ($request->admin_approval === 'approved by admin') {
                $query->where(function($q) {
                    $q->where('status', 'approved by admin')
                      ->orWhere('status', 'approved by supervisor');
                });
            } elseif ($request->admin_approval === 'rejected by admin') {
                $query->where('status', 'rejected by admin');
            }
        }

        // Filter berdasarkan status persetujuan supervisor
        if ($request->has('supervisor_approval') && !empty($request->supervisor_approval)) {
            if ($request->supervisor_approval === 'pending') {
                $query->where(function($q) {
                    $q->where('status', 'approved by admin')
                      ->orWhere('status', 'pending');
                });
            } elseif ($request->supervisor_approval === 'approved by supervisor') {
                $query->where('status', 'approved by supervisor');
            } elseif ($request->supervisor_approval === 'rejected by supervisor') {
                $query->where(function($q) {
                    $q->where('status', 'rejected by admin')
                      ->orWhere('status', 'rejected by supervisor');
                });
            }
        }

        // Eksekusi query dan kembalikan hasilnya dalam format DataTables
        $permintaans = $query->latest()->get();

        return DataTables::of($permintaans)
            ->addIndexColumn()
            ->addColumn('date', function ($row) {
                return $row->pilihan->first()->date ?? 'Tidak ada data';
            })
            ->addColumn('created_by', function ($row) {
                return $row->pilihan->first()->created_by ?? 'Tidak ada data';
            })
            ->addColumn('description', function ($row) {
                return $row->pilihan->first()->description ?? 'Tidak ada data';
            })
            ->addColumn('approval_status', function ($row) {
                // Status Admin
                $adminStatus = '';
                if ($row->status == 'pending') {
                    $adminStatus = '<button class="btn btn-secondary text-gray btn-sm font-size-13" style="border: 1px solid #505D69; color: #6b7280; background-color:#edeef0; pointer-events: none; cursor: not-allowed; margin-bottom: 0.5rem; opacity:0.7; padding: .1rem .25rem;">Admin Pending</button>';                    
                } elseif (($row->status == 'rejected by admin') || ($row->status == 'rejected by supervisor' && $row->ctt_adm !== NULL)) {
                    $adminStatus = '<button class="btn btn-secondary text-danger btn-sm font-size-13" style="border: 1px solid #F32F53; pointer-events: none; background-color: #feeef1; cursor: not-allowed; margin-bottom: 0.5rem; opacity:0.8; padding: .1rem .25rem;">Admin Rejected</button>';
                } elseif ($row->status == 'approved by admin' || ($row->status == 'rejected by supervisor' && $row->ctt_adm == NULL) || $row->status == 'approved by supervisor') {
                    $adminStatus = '<button class="btn btn-secondary text-success btn-sm font-size-13" style="border: 1px solid #46cf74; background-color:#f3fbf5; pointer-events: none; cursor: not-allowed; margin-bottom: 0.5rem; opacity:0.8; padding: .1rem .25rem;">Admin Approved</button>';
                }
    
                // Status Supervisor
                $supervisorStatus = '';
                if ($row->status == 'approved by admin' || $row->status == 'pending') {
                    $supervisorStatus = '<button class="btn btn-secondary text-gray btn-sm font-size-13" style="border: 1px solid #505D69; background-color:#edeef0; color: #6b7280; pointer-events: none; cursor: not-allowed; opacity:0.7; padding: .1rem .25rem;">Supervisor Pending</button>';
                } elseif ($row->status == 'rejected by supervisor' || $row->status == 'rejected by admin') {
                    $supervisorStatus = '<button class="btn btn-secondary text-danger btn-sm font-size-13" style="border: 1px solid #F32F53; background-color: #feeef1; pointer-events: none; cursor: not-allowed;; opacity:0.8; padding: .1rem .25rem;">Supervisor Rejected</button>';
                } elseif ($row->status == 'approved by supervisor') {
                    $supervisorStatus = '<button class="btn btn-secondary text-success btn-sm font-size-13" style="border: 1px solid #46cf74; background-color:#f3fbf5; pointer-events: none; cursor: not-allowed;; opacity:0.8; padding: .1rem .25rem;">Supervisor Approved</button>';
                }
    
                // Menggabungkan status admin dan supervisor
                return '<div class="d-flex flex-column align-items-start">' . $adminStatus . $supervisorStatus . '</div>';
            })
            ->addColumn('action', function ($row) {
                $viewButton = '<a href="'.route('permintaan.view', $row->id).'" class="btn btn-sm me-2 text-primary hover:bg-primary" style="width: 20px; height: 20px; display: flex; align-items: center; justify-content: center; text-decoration: none; color: blue; padding: 15px;" data-tooltip="Lihat Permintaan"><i class="ti ti-eye font-size-20 align-middle"></i></a>';
                
                $approveOrPrintButton = $row->status == 'approved by supervisor' ?
                    '<a href="'.route('permintaan.print', $row->id).'" class="btn btn-sm text-danger hover:bg-danger" style="width: 20px; height: 20px; display: flex; align-items: center; justify-content: center; text-decoration: none; color: red; padding: 15px;" data-tooltip="Cetak Permintaan"><i class="ti ti-printer font-size-20 align-middle text-danger"></i></a>' :
                    '<a href="'.route('permintaan.approve', $row->id).'" class="btn btn-sm ' . ($row->status == 'pending' ? 'hover:bg-success' : '') . '" style="width: 20px; height: 20px; display: flex; align-items: center; justify-content: center; text-decoration: none;' . ($row->status == 'pending' ? 'color: green;' : 'color: gray; pointer-events: none; opacity: 0.5;') . ' padding: 15px;" data-tooltip="Setujui Permintaan"><i class="ti ti-clipboard-check font-size-20 align-middle"></i></a>';

                return '<div class="text-center d-flex justify-content-center align-items-center">' . $viewButton . $approveOrPrintButton . '</div>';
            })
            ->rawColumns(['approval_status', 'action'])
            ->make(true);
    }

    // Mengambil status approval yang unik
    $statusAppr = Permintaan::where('user_id', $userId)->select('status')->distinct()->get();

    return view('backend.permintaan.permintaan_saya', compact('statusAppr'));
}

    public function permintaanDelete($id)
    {
        $permintaan = Permintaan::findOrFail($id);
        $permintaan->pilihan()->delete();
        $permintaan->delete();

        $notification = array(
            'message' => 'Permintaan berhasil dibatalkan',
            'alert-type' => 'success'
        );

        // Redirect kembali dengan notifikasi
        return redirect()->back()->with($notification);
    }

    public function PermintaanEdit($id)
    {
        // Ambil semua Pilihan berdasarkan permintaan_id
        $pilihan = Pilihan::where('permintaan_id', $id)->get();
        $barang = Barang::all();
        $kelompok = Kelompok::all();

        // Kirim data Pilihan, Barang, dan Kelompok ke view
        return view('backend.permintaan.permintaan_edit', compact('pilihan', 'barang', 'kelompok', 'id'));
    }


    public function PermintaanUpdate(Request $request, $id)
    {
        $permintaan_id = $request->input('permintaan_id');
        $tableData = $request->input('table_data');

        // Hapus entri lama berdasarkan permintaan_id
        Pilihan::where('permintaan_id', $permintaan_id)->delete();

        $tableData = json_decode($request->input('table_data'), true); // Decode JSON jika perlu

    if (is_array($tableData) && !empty($tableData)) {
        foreach ($tableData as $index => $item) {
            // Validasi data
            if (isset($item['kelompok_nama'], $item['barang_nama'], $item['qty_req'])) {
                // Temukan Barang dan Kelompok berdasarkan nama
                $barang = Barang::where('nama', $item['barang_nama'])->first();
                $kelompok = Kelompok::where('nama', $item['kelompok_nama'])->first();

                if ($barang && $kelompok) {
                    // Ekstrak angka dari qty_req
                    $qty_req_str = $item['qty_req'];
                    $qty_req = filter_var($qty_req_str, FILTER_SANITIZE_NUMBER_INT);
                    
                    // Validasi apakah qty_req adalah angka yang valid
                    if ($qty_req === false || !is_numeric($qty_req)) {
                        return redirect()->route('permintaan.saya')->with([
                            'message' => 'Kuantitas tidak valid',
                            'alert-type' => 'error'
                        ]);
                    }

                    // Buat entri Pilihan baru
                    $newPilihan = new Pilihan();
                    $newPilihan->permintaan_id = $permintaan_id;
                    $newPilihan->date = $item['date'] ?? null;
                    $newPilihan->description = $item['description'] ?? null;
                    $newPilihan->barang_id = $barang->id;
                    $newPilihan->req_qty = (int)$qty_req;
                    $newPilihan->pilihan_no = sprintf('P-%04d', $index + 1); // Atur sesuai kebutuhan
                    $newPilihan->created_by = Auth::user()->name;
                    $newPilihan->save(); // Simpan ke database

                    // Sesuaikan kuantitas barang
                    $barang->qty_item -= (int)$qty_req;
                    $barang->save();
                } else {
                    Log::info('Barang atau Kelompok tidak ditemukan:', ['barang' => $item['barang_nama'], 'kelompok' => $item['kelompok_nama']]);
                }
            } else {
                Log::info('Data item tidak valid:', ['item' => $item]);
            }
        }
    } else {
        // Jika data tabel tidak valid
        Log::info('Data tabel tidak valid:', ['data' => $tableData]);

        return redirect()->route('permintaan.saya')->with([
            'message' => 'Data tabel tidak valid',
            'alert-type' => 'error'
        ]);
    }

            

        // Notifikasi sukses dan redirect
        return redirect()->route('permintaan.saya')->with([
            'message' => 'Data berhasil diperbarui dan kuantitas barang diperbarui',
            'alert-type' => 'success'
        ]);
    }
    public function PermintaanPrint($id)
    {
        // Ambil data permintaan dan pilihan
        $permintaan = Permintaan::findOrFail($id);
        $pilihan = Pilihan::where('permintaan_id', $id)->get();
        
        // Load view sebagai HTML
        $view = view('backend.permintaan.permintaan_print', compact('permintaan', 'pilihan'))->render();
        
        // Inisialisasi Dompdf
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isPhpEnabled', true);
        
        $dompdf = new Dompdf($options);
        
        // Load HTML ke Dompdf
        $dompdf->loadHtml($view);
        
        // Atur ukuran kertas dan orientasi
        $dompdf->setPaper('A5', 'landscape');
        
        // Render PDF
        $dompdf->render();
        
        // Nama file berdasarkan no_permintaan
        $fileName = 'permintaan_' . $permintaan->no_permintaan . '.pdf';
        
        // Output PDF dengan nama file yang diinginkan
        return $dompdf->stream($fileName, array('Attachment' => 0));
    }
    


}
