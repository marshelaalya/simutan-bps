@extends(auth()->user()->role === 'admin' ? 'admin.admin_master' : 
         (auth()->user()->role === 'supervisor' ? 'supervisor.supervisor_master' : 
         'pegawai.pegawai_master'))

@section(auth()->user()->role === 'admin' ? 'admin' : 
         (auth()->user()->role === 'supervisor' ? 'supervisor' : 'pegawai'))


<script src="{{ mix('js/app.js') }}" defer></script>

<div class="page-content">
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Permintaan Saya</h4>
                    <div class="page-title-right">
                        <a href="{{ route('pilihan.add') }}" class="btn btn-info waves-effect waves-light">
                            <i class="mdi mdi-plus-circle"></i> Ajukan Permintaan
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <!-- end page title -->

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <h4 class="card-title mb-0">Daftar Permintaan Barang</h4>
                        </div>

                        <table id="datatable" class="table table-bordered dt-responsive nowrap" 
                    style="border-collapse: collapse; border-spacing: 0; width: 100%; table-layout: auto;">
                 <thead>
                     <tr>
                         <th width="6%">Tanggal</th>
                         <th width="12%">Nama Pegawai</th>
                         <th style="word-wrap: break-word; word-break: break-all; white-space: normal;">Catatan</th>
                         <th width="10%" class="text-center">Approval Admin</th>
                         <th width="12.5%" class="text-center">Approval Supervisor</th>
                         <th width="5%" class="text-center">Aksi</th>
                     </tr>
                        </thead>

                            <tbody>
                                @foreach($permintaans as $item)
                                    <tr>
                                        <td style="white-space: nowrap;">{{ $item->pilihan->first()->date ?? 'Tidak ada data' }}</td>
                                        <td style="white-space: nowrap;">{{ $item->pilihan->first()->created_by ?? 'Tidak ada data' }}</td>
                                        <td style="word-wrap: break-word; word-break: break-all; white-space: normal;">{{ $item->pilihan->first()->description ?? 'Tidak ada data' }}</td>
                                        <td class="text-center" style="white-space: nowrap;">
                                            @if($item->status == 'pending')
                                                <button class="btn btn-secondary bg-warning btn-sm font-size-13" 
                                                        style="border: 0; color: #ca8a04; pointer-events: none; cursor: not-allowed;">
                                                    Pending
                                                </button>
                                            @elseif($item->status == 'rejected by admin')
                                                <button class="btn btn-secondary bg-danger text-danger btn-sm font-size-13" 
                                                        style="border: 0; pointer-events: none; cursor: not-allowed;">
                                                    Rejected
                                                </button>
                                            @elseif($item->status == 'approved by admin')
                                                <button class="btn btn-secondary bg-success text-success btn-sm font-size-13" 
                                                        style="border: 0; pointer-events: none; cursor: not-allowed;">
                                                    Approved
                                                </button>
                                            @elseif($item->ctt_adm == NULL && $item->status == 'rejected by supervisor')
                                                <button class="btn btn-secondary bg-success text-success btn-sm font-size-13" 
                                                        style="border: 0; pointer-events: none; cursor: not-allowed;">
                                                    Approved
                                                </button>
                                            @endif
                                        </td>
                                        <td class="text-center" style="white-space: nowrap;">
                                            @if($item->status == 'approved by admin' || $item->status == 'pending')
                                                <button class="btn btn-secondary bg-warning btn-sm font-size-13" 
                                                        style="border: 0; color: #ca8a04; pointer-events: none; cursor: not-allowed;">
                                                    Pending
                                                </button>
                                            @elseif($item->status == 'rejected by supervisor')
                                                <button class="btn btn-secondary bg-danger text-danger btn-sm font-size-13" 
                                                        style="border: 0; pointer-events: none; cursor: not-allowed;">
                                                    Rejected
                                                </button>
                                            @elseif($item->status == 'rejected by admin')
                                                <button class="btn btn-secondary bg-danger text-danger btn-sm font-size-13" 
                                                        style="border: 0; pointer-events: none; cursor: not-allowed;">
                                                    Rejected
                                                </button>
                                            @elseif($item->status == 'approved by supervisor')
                                                <button class="btn btn-secondary bg-success text-success btn-sm font-size-13" 
                                                        style="border: 0; pointer-events: none; cursor: not-allowed;">
                                                    Approved
                                                </button>
                                            @endif
                                        </td>
                                        <td class="text-center d-flex justify-content-center align-items-center" style="white-space: nowrap;"> 
                                            @if($item->status == 'pending')
                                                <a href="{{ route('permintaan.view', $item->id) }}" class="btn bg-primary btn-sm me-2" style="width: 30px; height: 30px; padding: 0; display: flex; align-items: center; justify-content: center; text-decoration: none;">
                                                    <i class="ri-eye-fill font-size-16 align-middle text-primary"></i>
                                                </a>
                                                <a href="{{ route('permintaan.edit', $item->id) }}" class="btn bg-warning btn-sm me-2" style="width: 30px; height: 30px; padding: 0; display: flex; align-items: center; justify-content: center; text-decoration: none;">
                                                    <i class="fas fa-edit align-middle" style="color: #ca8a04"></i>
                                                </a>
                                                <a href="{{ route('permintaan.delete', $item->id) }}" class="btn bg-danger btn-sm me-2 btn-delete" style="width: 30px; height: 30px; padding: 0; display: flex; align-items: center; justify-content: center; text-decoration: none;">
                                                    <i class="fas fa-trash-alt text-danger"></i>
                                                </a>                                                                                              
                                            @elseif($item->status == 'approved by admin' || $item->status == 'rejected by supervisor' || $item->status == 'finished' || $item->status == 'rejected by admin')
                                                <a href="{{ route('permintaan.view', $item->id) }}" class="btn bg-primary btn-sm" style="width: 30px; height: 30px; padding: 0; display: flex; align-items: center; justify-content: center; text-decoration: none;">
                                                    <i class="ri-eye-fill font-size-16 align-middle text-primary"></i>
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
