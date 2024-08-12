@extends(auth()->user()->role === 'admin' ? 'admin.admin_master' : 
         (auth()->user()->role === 'supervisor' ? 'supervisor.supervisor_master' : 
         'pegawai.pegawai_master'))

@section(auth()->user()->role === 'admin' ? 'admin' : 
         (auth()->user()->role === 'supervisor' ? 'supervisor' : 'pegawai'))



<div class="page-content">
<div class="container-fluid">

<!-- start page title -->
<div class="row">
<div class="col-12">
<div class="page-title-box d-sm-flex align-items-center justify-content-between">
    <h4 class="mb-sm-0 text-info">Dashboard</h4>

    <div class="page-title-right">
        <ol class="breadcrumb m-0">
            <li class="breadcrumb-item"><a href="javascript: void(0);">SIMUTAN</a></li>
            <li class="breadcrumb-item active">Dashboard</li>
        </ol>
    </div>

</div>
</div>
</div>
<!-- end page title -->
<h2 class="mb-4 fw-bold">Selamat Datang di SIMUTAN! </h2>
{{-- <p class="mb-4">Sistem Mutasi Persediaan (SIMUTAN) adalah sistem yang digunakan untuk mengetahui jumlah dan mutasi barang serta menilai dari selisih kelebihan atau kekurangan barang.</p> --}}
<div class="row gx-4">
<div class="col-xl-3 col-md-6">
<div class="card">
    <div class="card-body">
        <div class="d-flex">
            <div class="flex-grow-1">
                <h5 class="font-size-14 mb-1">Total Permintaan<br>Diajukan</h5>
                <h6 class="mb-0 text-muted small">Bulan Ini</h6>
                <h4 class="mb-2 mt-2">{{ $totalPermintaanBulanIni ?? 'Tidak ada data' }}</h4>
            </div>
            <div class="avatar-sm">
                <span class="avatar-title bg-primary text-primary rounded-3">
                    <i class="mdi mdi-clipboard-text-multiple-outline font-size-24"></i>  
                </span>
            </div>
        </div>                                            
    </div><!-- end cardbody -->
</div><!-- end card -->
</div><!-- end col -->
<div class="col-xl-3 col-md-6">
<div class="card">
    <div class="card-body">
        <div class="d-flex">
            <div class="flex-grow-1">
                <h5 class="font-size-14 mb-1">Total Permintaan<br>Selesai</h5>
                <h6 class="mb-0 text-muted small">Bulan Ini</h6>
                <h4 class="mb-2 mt-2">{{ $totalPermintaanSelesai ?? 'Tidak ada data' }}</h4>
            </div>
            <div class="avatar-sm">
                <span class="avatar-title bg-success text-success rounded-3">
                    <i class="mdi mdi-clipboard-check-outline font-size-24"></i>  
                </span>
            </div>
        </div>                                              
    </div><!-- end cardbody -->
</div><!-- end card -->
</div><!-- end col -->
<div class="col-xl-3 col-md-6">
<div class="card">
    <div class="card-body">
        <div class="d-flex">
            <div class="flex-grow-1">
                <h5 class="font-size-14 mb-1">Total Permintaan<br>Pending</h5>
                <h6 class="mb-0 text-muted small">Bulan Ini</h6>
                <h4 class="mb-2 mt-2">{{ $totalPermintaanPending ?? 'Tidak ada data' }}</h4>
            </div>
            <div class="avatar-sm">
                <span class="avatar-title bg-warning text-warning rounded-3">
                    <i class="mdi mdi-clipboard-clock-outline font-size-24"></i>  
                </span>
            </div>
        </div>                                              
    </div><!-- end cardbody -->
</div><!-- end card -->
</div><!-- end col -->
<div class="col-xl-3 col-md-6">
<div class="card">
    <div class="card-body">
        <div class="d-flex">
            <div class="flex-grow-1">
                <h5 class="font-size-14 mb-1">Total Permintaan<br>Ditolak</h5>
                <h6 class="mb-0 text-muted small">Bulan Ini</h6>
                <h4 class="mb-2 mt-2">{{ $totalPermintaanRejected ?? 'Tidak ada data' }}</h4>
            </div>
            <div class="avatar-sm">
                <span class="avatar-title bg-danger text-danger rounded-3">
                    <i class="mdi mdi-clipboard-remove-outline font-size-24"></i>  
                </span>
            </div>
        </div>                                              
    </div><!-- end cardbody -->
</div><!-- end card -->
</div><!-- end col -->
</div><!-- end row -->

<div class="row">
 

<div class="row">
<div class="col-xl-12">
    <h4 class="card-title mb-3 text-info">Permintaan Terbaru</h4>
    {{-- <hr style="border: 1px solid #043277"> --}}
<div class="card">
    <div class="card-body">
        {{-- <div class="dropdown float-end">
            <a href="#" class="dropdown-toggle arrow-none card-drop" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="mdi mdi-dots-vertical"></i>
            </a>
         
        </div> --}}

        
        <div class="table-responsive">
            <table id="datatable" class="table table-bordered dt-responsive nowrap" 
                        style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead>
                            <tr>
                                <th width = 1%>Tanggal</th> {{-- Tanggal Permintaan --}}
                                <th width = 1%>Nama Pegawai</th>
                                <th>Catatan</th>
                                <th width = 1% class="text-center">Approval Admin</th>
                                <th width = 1% class="text-center">Approval Supervisor</th>
                                <th width = 1% class="text-center">Aksi</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($permintaans as $key => $item)
                                <tr>
                                    <td>
                                        {{ $item->pilihan->first()->date ?? 'Tidak ada data' }}
                                    </td>
                                    <td>
                                        {{ $item->pilihan->first()->created_by ?? 'Tidak ada data' }}
                                    </td>
                                    <td>
                                        {{ $item->pilihan->first()->description ?? 'Tidak ada data' }}
                                    </td>
                                    <td class="text-center align-middle justify-content-center">
                                        @if($item->status == 'pending')
                                            <button class="btn btn-secondary bg-warning btn-sm font-size-13" 
                                                    style="border: 0; color: #ca8a04; pointer-events: none; cursor: not-allowed;">
                                                Pending
                                            </button>
                                        @elseif($item->status == 'rejected by admin')
                                            <button class="btn btn-secondary bg-danger btn-sm font-size-13" 
                                                    style="border: 0; color: #fff; pointer-events: none; cursor: not-allowed;">
                                                Rejected
                                            </button>
                                        @elseif($item->status == 'approved by admin')
                                            <button class="btn btn-secondary bg-success text-success btn-sm font-size-13" 
                                                    style="border: 0; pointer-events: none; cursor: not-allowed;">
                                                Approved
                                            </button>
                                        @endif
                                    </td>
                                    <td class="text-center align-middle justify-content-center">
                                        @if($item->status == 'approved by admin' || $item->status == 'pending')
                                            <button class="btn btn-secondary bg-warning btn-sm font-size-13" 
                                                    style="border: 0; color: #ca8a04; pointer-events: none; cursor: not-allowed;">
                                                Pending
                                            </button>
                                        @elseif($item->status == 'rejected by supervisor')
                                            <button class="btn btn-secondary bg-danger btn-sm font-size-13" 
                                                    style="border: 0; color: #fff; pointer-events: none; cursor: not-allowed;">
                                                Rejected
                                            </button>
                                        @elseif($item->status == 'approved by supervisor')
                                            <button class="btn btn-secondary bg-success text-success btn-sm font-size-13" 
                                                    style="border: 0; pointer-events: none; cursor: not-allowed;">
                                                Approved
                                            </button>
                                        @endif
                                    </td>
                                    
                                    <td class="text-center">
                                        <a href="{{ route('permintaan.view', $item->id) }}" class="btn bg-primary btn-sm me-2" style="width: 30px; height: 30px; padding: 0; display: flex; align-items: center; justify-content: center; text-decoration: none;">
                                            <i class="ri-eye-fill font-size-16 align-middle text-primary"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
        </div>
        <div class="d-flex justify-content-end fw-bold">
            <a href="{{ route('permintaan.all') }}" class="text-info">Lihat Selengkapnya <i class=" mdi mdi-arrow-right font-size-16 text-info align-middle"></i></a>
        </div>                
    </div><!-- end card -->
</div><!-- end card -->
</div>
<!-- end col -->
 


</div>
<!-- end row -->
</div>

</div>

<script>
    $(document).ready(function() {
    $('#datatable').DataTable({
        initComplete: function() {
            $('#datatable thead').css('background-color', '#043277').css('color', 'white');
        }
    });
});

</script>

@endsection