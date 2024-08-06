@extends('pegawai.pegawai_master')
@section('pegawai')


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
                <h5 class="font-size-14 mb-1">Total Permintaan Diajukan</h5>
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
                <h5 class="font-size-14 mb-1">Total Permintaan Selesai</h5>
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
                <h5 class="font-size-14 mb-1">Total Permintaan Pending</h5>
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
                <h5 class="font-size-14 mb-1">Total Permintaan Ditolak</h5>
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
<div class="card">
    <div class="card-body">
        {{-- <div class="dropdown float-end">
            <a href="#" class="dropdown-toggle arrow-none card-drop" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="mdi mdi-dots-vertical"></i>
            </a>
         
        </div> --}}

        <h4 class="card-title mb-4 text-info">Permintaan Terbaru</h4>

        <div class="table-responsive">
            <table class="table table-centered mb-0 align-middle table-hover table-nowrap">
                <thead class="table-light">
                    <tr>
                        {{-- <th>No Permintaan</th> --}}
                        <th>Tanggal</th>
                        <th>Nama Pengaju</th>
                        <th>Catatan</th>
                        <th class="text-center">Approval Admin</th>
                        <th class="text-center">Approval Supervisor</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead><!-- end thead -->
                <tbody>
                    <tr>
                        {{-- <td><h6 class="mb-0">B-120/31751/PL.711/1/2024</h6></td> --}}
                        <td>2024-08-01</td>
                        <td>Fia</td>
                        <td>Permintaan Barang</td>
                        <td style="text-align: center; vertical-align: middle;">
                            <button class="btn btn-secondary bg-success btn-sm btn-disabled d-flex align-items-center justify-content-center font-size-13" 
                                    style=" border: 0px; color: #16a34a;
                                           pointer-events: none; cursor: not-allowed; 
                                               display: flex; align-items: center; justify-content: center !important; margin: 0 auto;
                                                display: block;">
                                Approved
                            </button>                         
                        </td> 
                            {{-- <div class="d-flex align-items-center justify-content-center font-size-13">
                                <i class="ri-checkbox-blank-circle-fill font-size-10 text-warning me-2"></i> 
                                Pending
                            </div> --}}
                            
                            <td style="text-align: center; vertical-align: middle;">
                                <button class="btn btn-secondary bg-warning btn-sm btn-disabled d-flex align-items-center justify-content-center font-size-13" 
                                        style=" border: 0px solid #ffc107; color: #ca8a04;
                                               pointer-events: none; cursor: not-allowed; 
                                               display: flex; align-items: center; justify-content: center !important; margin: 0 auto;
                                                display: block; ">
                                    Pending
                                </button>                         
                            </td>                            
                        
                        <td style="text-align: center; vertical-align: middle;">
                            <button class="btn bg-warning btn-sm">
                                <i class="ri-eye-fill font-size-16 align-middle" style="color: #ca8a04"></i>
                            </button>
                            <button class="btn bg-danger btn-sm">
                                <i class="ri-printer-fill font-size-16 text-danger align-middle"></i>
                            </button>
                        </td>
                    </tr>
                     <!-- end -->
                     <tr>
                        {{-- <td><h6 class="mb-0">B-120/31751/PL.711/1/2024</h6></td> --}}
                        <td>2024-07-29</td>
                        <td>Fia</td>
                        <td>Permintaan Barang</td>
                        <td style="text-align: center; vertical-align: middle;">
                            <button class="btn btn-secondary bg-warning btn-sm btn-disabled d-flex align-items-center justify-content-center font-size-13" 
                                    style=" border: 0px solid #ffc107; color: #ca8a04;
                                           pointer-events: none; cursor: not-allowed; 
                                           display: flex; align-items: center; justify-content: center !important; margin: 0 auto;
                                            display: block; ">
                                Pending
                            </button>                         
                        </td>  
                            {{-- <div class="d-flex align-items-center justify-content-center font-size-13">
                                <i class="ri-checkbox-blank-circle-fill font-size-10 text-warning me-2"></i> 
                                Pending
                            </div> --}}
                            
                            <td style="text-align: center; vertical-align: middle;">
                                <button class="btn btn-secondary bg-warning btn-sm btn-disabled d-flex align-items-center justify-content-center font-size-13" 
                                        style=" border: 0px solid #ffc107; color: #ca8a04;
                                               pointer-events: none; cursor: not-allowed; 
                                               display: flex; align-items: center; justify-content: center !important; margin: 0 auto;
                                                display: block; ">
                                    Pending
                                </button>                         
                            </td>                            
                        
                        <td style="text-align: center; vertical-align: middle;">
                            <button class="btn bg-warning btn-sm">
                                <i class="ri-eye-fill font-size-16 align-middle" style="color: #ca8a04"></i>
                            </button>
                            <button class="btn bg-danger btn-sm">
                                <i class="ri-printer-fill font-size-16 text-danger align-middle"></i>
                            </button>
                        </td>
                    </tr>
                    <tr>
                        {{-- <td><h6 class="mb-0">B-120/31751/PL.711/1/2024</h6></td> --}}
                        <td>2024-07-29</td>
                        <td>Fia</td>
                        <td>Permintaan Barang</td>
                        <td style="text-align: center; vertical-align: middle;">
                            <button class="btn btn-secondary bg-warning btn-sm btn-disabled d-flex align-items-center justify-content-center font-size-13" 
                                    style=" border: 0px solid #ffc107; color: #ca8a04;
                                           pointer-events: none; cursor: not-allowed; 
                                           display: flex; align-items: center; justify-content: center !important; margin: 0 auto;
                                            display: block; ">
                                Pending
                            </button>                         
                        </td>  
                            {{-- <div class="d-flex align-items-center justify-content-center font-size-13">
                                <i class="ri-checkbox-blank-circle-fill font-size-10 text-warning me-2"></i> 
                                Pending
                            </div> --}}
                            
                            <td style="text-align: center; vertical-align: middle;">
                                <button class="btn btn-secondary bg-warning btn-sm btn-disabled d-flex align-items-center justify-content-center font-size-13" 
                                        style=" border: 0px solid #ffc107; color: #ca8a04;
                                               pointer-events: none; cursor: not-allowed; 
                                               display: flex; align-items: center; justify-content: center !important; margin: 0 auto;
                                                display: block; ">
                                    Pending
                                </button>                         
                            </td>                            
                        
                        <td style="text-align: center; vertical-align: middle;">
                            <button class="btn bg-warning btn-sm">
                                <i class="ri-eye-fill font-size-16 align-middle" style="color: #ca8a04"></i>
                            </button>
                            <button class="btn bg-danger btn-sm">
                                <i class="ri-printer-fill font-size-16 text-danger align-middle"></i>
                            </button>
                        </td>
                    </tr>
                     <!-- end -->
                     <tr>
                        {{-- <td><h6 class="mb-0">B-120/31751/PL.711/1/2024</h6></td> --}}
                        <td>2024-07-29</td>
                        <td>Fia</td>
                        <td>Permintaan Barang</td>
                        <td style="text-align: center; vertical-align: middle;">
                            <button class="btn btn-secondary bg-success btn-sm btn-disabled d-flex align-items-center justify-content-center font-size-13" 
                                    style=" border: 0px; color: #16a34a;
                                           pointer-events: none; cursor: not-allowed; 
                                               display: flex; align-items: center; justify-content: center !important; margin: 0 auto;
                                                display: block;">
                                Approved
                            </button>                         
                        </td>  
                            {{-- <div class="d-flex align-items-center justify-content-center font-size-13">
                                <i class="ri-checkbox-blank-circle-fill font-size-10 text-warning me-2"></i> 
                                Pending
                            </div> --}}
                            
                            <td style="text-align: center; vertical-align: middle;">
                                <button class="btn btn-secondary bg-warning btn-sm btn-disabled d-flex align-items-center justify-content-center font-size-13" 
                                        style=" border: 0px solid #ffc107; color: #ca8a04;
                                               pointer-events: none; cursor: not-allowed; 
                                               display: flex; align-items: center; justify-content: center !important; margin: 0 auto;
                                                display: block; ">
                                    Pending
                                </button>                         
                            </td>                            
                        
                        <td style="text-align: center; vertical-align: middle;">
                            <button class="btn bg-warning btn-sm">
                                <i class="ri-eye-fill font-size-16 align-middle" style="color: #ca8a04"></i>
                            </button>
                            <button class="btn bg-danger btn-sm">
                                <i class="ri-printer-fill font-size-16 text-danger align-middle"></i>
                            </button>
                        </td>
                    </tr>
                     <!-- end -->
                     <tr>
                        {{-- <td><h6 class="mb-0">B-120/31751/PL.711/1/2024</h6></td> --}}
                        <td>2024-07-29</td>
                        <td>Fia</td>
                        <td>Permintaan Barang</td>
                        <td style="text-align: center; vertical-align: middle;">
                            <button class="btn btn-secondary bg-warning btn-sm btn-disabled d-flex align-items-center justify-content-center font-size-13" 
                                    style=" border: 0px solid #ffc107; color: #ca8a04;
                                           pointer-events: none; cursor: not-allowed; 
                                           display: flex; align-items: center; justify-content: center !important; margin: 0 auto;
                                            display: block; ">
                                Pending
                            </button>                         
                        </td>  
                            {{-- <div class="d-flex align-items-center justify-content-center font-size-13">
                                <i class="ri-checkbox-blank-circle-fill font-size-10 text-warning me-2"></i> 
                                Pending
                            </div> --}}
                            
                            <td style="text-align: center; vertical-align: middle;">
                                <button class="btn btn-secondary bg-warning btn-sm btn-disabled d-flex align-items-center justify-content-center font-size-13" 
                                        style=" border: 0px solid #ffc107; color: #ca8a04;
                                               pointer-events: none; cursor: not-allowed; 
                                               display: flex; align-items: center; justify-content: center !important; margin: 0 auto;
                                                display: block; ">
                                    Pending
                                </button>                         
                            </td>                            
                        
                        <td style="text-align: center; vertical-align: middle;">
                            <button class="btn bg-warning btn-sm">
                                <i class="ri-eye-fill font-size-16 align-middle" style="color: #ca8a04"></i>
                            </button>
                            <button class="btn bg-danger btn-sm">
                                <i class="ri-printer-fill font-size-16 text-danger align-middle"></i>
                            </button>
                        </td>
                    </tr>
                     <!-- end -->
                     {{-- <tr> --}}
                        {{-- <td><h6 class="mb-0">B-120/31751/PL.711/1/2024</h6></td> --}}
                        {{-- <td>2024-08-01</td>
                        <td>Fia</td>
                        <td>Permintaan Barang</td>
                        <td style="text-align: center; vertical-align: middle;">
                            <div class="font-size-13">
                                <i class="ri-checkbox-blank-circle-fill font-size-10 text-success align-middle me-2"></i>
                                Approved
                            </div>
                        </td>                        
                            
                        <td style="text-align: center; vertical-align: middle;">
                            <div class="font-size-13">
                                <i class="ri-checkbox-blank-circle-fill font-size-10 text-danger align-middle me-2"></i>
                                Rejected
                            </div>
                        </td>                            
                        
                        <td>
                            <button class="btn bg-warning btn-sm">
                                <i class="ri-eye-fill font-size-16 align-middle" style="color: #ca8a04"></i>
                            </button>
                            <button class="btn bg-danger btn-sm">
                                <i class="ri-printer-fill font-size-16 text-danger align-middle"></i>
                            </button>
                        </td>
                    </tr> --}}
                     
                     <!-- end -->
                </tbody><!-- end tbody -->
            </table> <!-- end table -->
        </div>
        <div class="d-flex justify-content-end mt-4">
            <a href="#" class="text-info">Lihat Selengkapnya <i class=" mdi mdi-arrow-right font-size-16 text-info align-middle"></i></a>
        </div>                
    </div><!-- end card -->
</div><!-- end card -->
</div>
<!-- end col -->
 


</div>
<!-- end row -->
</div>

</div>

@endsection