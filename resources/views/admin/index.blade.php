@extends('admin.admin_master')
@section('admin')


<div class="page-content">
<div class="container-fluid">

<!-- start page title -->
<div class="row">
<div class="col-12">
<div class="page-title-box d-sm-flex align-items-center justify-content-between">
    <h4 class="mb-sm-0">Dashboard</h4>

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
<div class="row gx-4">
<div class="col-xl-3 col-md-6">
<div class="card">
    <div class="card-body">
        <div class="d-flex">
            <div class="flex-grow-1">
                <h5 class="font-size-14 mb-1">Total Permintaan Diajukan</h5>
                <h6 class="mb-0 text-muted small">Bulan Ini</h6>
                <h4 class="mb-2 mt-2">58</h4>
                {{-- <p class="text-muted mb-0"><span class="text-success fw-bold font-size-12 me-2"><i class="ri-arrow-right-up-line me-1 align-middle"></i>9.23%</span>from previous period</p> --}}
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
                <h4 class="mb-2 mt-2">34</h4>
                {{-- <p class="text-muted mb-0"><span class="text-danger fw-bold font-size-12 me-2"><i class="ri-arrow-right-down-line me-1 align-middle"></i>1.09%</span>from previous period</p> --}}
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
                <h5 class=" font-size-14 mb-1">Total Permintaan Pending</h5>
                <h6 class="mb-0 text-muted small">Bulan Ini</h6>
                <h4 class="mb-2 mt-2">12</h4>
                {{-- <p class="text-muted mb-0"><span class="text-success fw-bold font-size-12 me-2"><i class="ri-arrow-right-up-line me-1 align-middle"></i>16.2%</span>from previous period</p> --}}
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
                <h4 class="mb-2 mt-2">20</h4>


                {{-- <p class="text-muted mb-0"><span class="text-success fw-bold font-size-12 me-2"><i class="ri-arrow-right-up-line me-1 align-middle"></i>11.7%</span>from previous period</p> --}}
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
        <div class="dropdown float-end">
            <a href="#" class="dropdown-toggle arrow-none card-drop" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="mdi mdi-dots-vertical"></i>
            </a>
         
        </div>

        <h4 class="card-title mb-4">Permintaan Terbaru</h4>

        <div class="table-responsive">
            <table class="table table-centered mb-0 align-middle table-hover table-nowrap">
                <thead class="table-light">
                    <tr>
                        <th>No Permintaan</th>
                        <th>Tanggal</th>
                        <th>Nama Pengaju</th>
                        <th>Catatan</th>
                        <th>Approve Admin</th>
                        <th>Approve Supervisor</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead><!-- end thead -->
                <tbody>
                    <tr>
                        <td><h6 class="mb-0">B-120/31751/PL.711/1/2024</h6></td>
                        <td>2024-08-01</td>
                        <td>Fia</td>
                        <td>Permintaan Barang</td>
                        <td>
                            <div class="d-flex align-items-center justify-content-center font-size-13">
                                <i class="ri-checkbox-blank-circle-fill font-size-10 text-success me-2"></i> 
                                Approved
                            </div>  
                        </td>
                            {{-- <div class="d-flex align-items-center justify-content-center font-size-13">
                                <i class="ri-checkbox-blank-circle-fill font-size-10 text-warning me-2"></i> 
                                Pending
                            </div> --}}
                            
                            <td style="text-align: center; vertical-align: middle;">
                                <button class="btn btn-secondary btn-sm me-2 btn-disabled d-flex align-items-center justify-content-center font-size-13" 
                                        style="background-color: #ffc107; color: #000000; border: 1px solid #ffc107; 
                                               pointer-events: none; opacity: 0.6; cursor: not-allowed; 
                                               display: flex; align-items: center; justify-content: center !important;">
                                    Pending
                                </button>                         
                            </td>                            
                        
                        <td>
                            <button class="btn bg-dark btn-sm">
                                <i class="ri-eye-fill font-size-16 text-gray align-middle"></i>
                            </button>
                            <button class="btn bg-dark btn-sm">
                                <i class="ri-printer-fill font-size-16 text-gray align-middle"></i>
                            </button>
                        </td>
                    </tr>
                     <!-- end -->
                     <tr>
                        <td><h6 class="mb-0">Alex Adams</h6></td>
                        <td>Python Developer</td>
                        <td>
                            <div class="font-size-13"><i class="ri-checkbox-blank-circle-fill font-size-10 text-warning align-middle me-2"></i>Deactive</div>
                        </td>
                        <td>
                            28
                        </td>
                        <td>
                            01 Aug, 2021
                        </td>
                        <td>$25,060</td>
                    </tr>
                     <!-- end -->
                     <tr>
                        <td><h6 class="mb-0">Prezy Kelsey</h6></td>
                        <td>Senior Javascript Developer</td>
                        <td>
                            <div class="font-size-13"><i class="ri-checkbox-blank-circle-fill font-size-10 text-success align-middle me-2"></i>Active</div>
                        </td>
                        <td>
                            35
                        </td>
                        <td>
                            15 Jun, 2021
                        </td>
                        <td>$59,350</td>
                    </tr>
                     <!-- end -->
                     <tr>
                        <td><h6 class="mb-0">Ruhi Fancher</h6></td>
                        <td>React Developer</td>
                        <td>
                            <div class="font-size-13"><i class="ri-checkbox-blank-circle-fill font-size-10 text-success align-middle me-2"></i>Active</div>
                        </td>
                        <td>
                            25
                        </td>
                        <td>
                            01 March, 2021
                        </td>
                        <td>$23,700</td>
                    </tr>
                     <!-- end -->
                     <tr>
                        <td><h6 class="mb-0">Juliet Pineda</h6></td>
                        <td>Senior Web Designer</td>
                        <td>
                            <div class="font-size-13"><i class="ri-checkbox-blank-circle-fill font-size-10 text-success align-middle me-2"></i>Active</div>
                        </td>
                        <td>
                            38
                        </td>
                        <td>
                            01 Jan, 2021
                        </td>
                        <td>$69,185</td>
                    </tr>
                     <!-- end -->
                     <tr>
                        <td><h6 class="mb-0">Den Simpson</h6></td>
                        <td>Web Designer</td>
                        <td>
                            <div class="font-size-13"><i class="ri-checkbox-blank-circle-fill font-size-10 text-warning align-middle me-2"></i>Deactive</div>
                        </td>
                        <td>
                            21
                        </td>
                        <td>
                            01 Sep, 2021
                        </td>
                        <td>$37,845</td>
                    </tr>
                     <!-- end -->
                     <tr>
                        <td><h6 class="mb-0">Mahek Torres</h6></td>
                        <td>Senior Laravel Developer</td>
                        <td>
                            <div class="font-size-13"><i class="ri-checkbox-blank-circle-fill font-size-10 text-success align-middle me-2"></i>Active</div>
                        </td>
                        <td>
                            32
                        </td>
                        <td>
                            20 May, 2021
                        </td>
                        <td>$55,100</td>
                    </tr>
                     <!-- end -->
                </tbody><!-- end tbody -->
            </table> <!-- end table -->
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