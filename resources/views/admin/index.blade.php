@extends(auth()->user()->role === 'admin' ? 'admin.admin_master' : 
         (auth()->user()->role === 'supervisor' ? 'supervisor.supervisor_master' : 
         'pegawai.pegawai_master'))

@section(auth()->user()->role === 'admin' ? 'admin' : 
         (auth()->user()->role === 'supervisor' ? 'supervisor' : 'pegawai'))

{{-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"> --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        .tooltip-text {
            display: none;
            position: absolute;
            bottom: 0;
            left: 0;
            transform: translateY(100%);
            white-space: nowrap;
            z-index: 6;
        }
    
        .position-relative:hover .tooltip-text {
            display: block;
        }
    
        .position-relative {
            position: relative;
        }
    
        .quarter-circle {
            position: absolute;
            border-radius: 50% 0 50% 50%;
            background-color: rgba(0, 123, 255, 0.3);
            z-index: 5;
        }
    
        .large-circle {
            width: 40px;
            height: 40px;
            top: 0;
            right: 0;
        }
    
        .small-circle {
            width: 30px;
            height: 30px;
            background-color: rgba(0, 123, 255, 0.7);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 1.25rem;
            font-weight: bold;
            top: 0;
            right: 0;
            margin: 0;
        }
    
        .tooltip-text {
            top: 0;
            right: 0;
            transform: translate(0, 0);
        }
    
        .gradient-background::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(to bottom, rgba(0, 123, 255, 0.5), rgba(0, 123, 255, 0) 80%);
            z-index: 1;
            pointer-events: none;
            border-radius: 0.375rem;
        }
    
        .img-fluid {
            position: relative;
            z-index: 2;
        }
    
        .leaderboard-container {
            display: flex;
            justify-content: center;
            align-items: flex-end;
            gap: 20px;
        }
    
        .leaderboard-item {
            /* text-align: center; */
        }
    
        .leaderboard-item:nth-child(1) {
            order: 2;
            transform: translateY(-10%);
        }
    
        .leaderboard-item:nth-child(2) {
            order: 1;
        }
    
        .leaderboard-item:nth-child(3) {
            order: 3;
            transform: translateY(5%);
        }

        .card, .gradient-background {
    border-radius: 0.375rem; /* Sesuaikan dengan kebutuhan */
    overflow: hidden; /* Supaya elemen di dalamnya tidak keluar dari border-radius */
}

.overlay-label {
        bottom: 10px;
        left: 0;
        background-color: rgba(0, 123, 255, 0.7);

        /* backgorund-color: rgba(104, 185, 46, 0.7); */
        color: #fff;
        width: 75%; /* 3/4 dari lebar gambar */
        padding: 5px;
        border-radius: 0 15px 15px 0;
        /* text-align: center; */
        transform: translateX(-100%);
        transition: transform 1s ease-in-out;
        z-index: 5;
    }

    .position-relative:hover .overlay-label {
        transform: translateX(0);
    }

    /* Animasi untuk gambar naik dari bawah */
    @keyframes slide-up {
        from {
            transform: translateY(100%);
            opacity: 0;
        }
        to {
            transform: translateY(0);
            opacity: 1;
        }
    }

    .animate-up {
        animation: slide-up 1s ease-out;
    }
    </style>

<div class="page-content">
<div class="container-fluid">

{{-- <!-- start page title -->
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
<!-- end page title --> --}}
<h2 class="mb-4 fw-bold">Selamat Datang di SIMUTAN! </h2>
{{-- <p class="mb-4">Sistem Mutasi Persediaan (SIMUTAN) adalah sistem yang digunakan untuk mengetahui jumlah dan mutasi barang serta menilai dari selisih kelebihan atau kekurangan barang.</p> --}}
<div class="row gx-4">
<div class="col-xl-3 col-md-6">
<div class="card">
    <div class="card-body">
        <div class="d-flex">
            <div class="flex-grow-1">
                <h5 class="font-size-14 mb-1">Total Permintaan</h5>
                <h4 class="font-size-20 mb-1 text-info"><strong>Diajukan</strong></h4>
                <h6 class="mb-0 text-muted small">Bulan Ini</h6>
                <h4 class="mb-0 mt-2">{{ $totalPermintaanBulanIni ?? 'Tidak ada data' }}</h4>
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
                <h5 class="font-size-14 mb-1">Total Permintaan</h5>
                <h4 class="font-size-20 mb-1 text-info"><strong>Selesai</strong></h4>
                <h6 class="mb-0 text-muted small">Bulan Ini</h6>
                <h4 class="mb-0 mt-2">{{ $totalPermintaanSelesai ?? 'Tidak ada data' }}</h4>
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
                <h5 class="font-size-14 mb-1">Total Permintaan</h5>
                <h4 class="font-size-20 mb-1 text-info"><strong>Pending</strong></h4>
                <h6 class="mb-0 text-muted small">Bulan Ini</h6>
                <h4 class="mb-0 mt-2">{{ $totalPermintaanPending ?? 'Tidak ada data' }}</h4>
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
                <h5 class="font-size-14 mb-1">Total Permintaan</h5>
                <h4 class="font-size-20 mb-1 text-info"><strong>Ditolak</strong></h4>
                <h6 class="mb-0 text-muted small">Bulan Ini</h6>
                <h4 class="mb-0 mt-2">{{ $totalPermintaanRejected ?? 'Tidak ada data' }}</h4>
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

<div class="row gx-4">
    <!-- Chart for Top Score Barang Diminta -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-3 text-info">Top 5 Barang Diminta</h4>
                <canvas id="myChartBarang" width="400" height="237" style="max-width: 100%;"></canvas>
            </div>
        </div>
    </div>

    <!-- Top Score Request User -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-3 text-info" style="padding-bottom: 25px">Top Score Request User</h4>
                <div class="leaderboard-container">
                    <!-- User 1 -->
                    <div class="leaderboard-item">
                        <div class="position-relative gradient-background">
                            <img src="{{ asset('backend/assets/images/users/16.png') }}" class="img-fluid rounded animate-up" alt="User 1" style="width: 100%; height: auto; aspect-ratio: 9/16;">
                            <div class="quarter-circle large-circle"></div>
                            <div class="quarter-circle small-circle">1</div>
                            <div class="overlay-label position-absolute">
                                <strong>Juni</strong><br>
                                15 requests
                            </div>
                            {{-- <div class="tooltip-text position-absolute bg-dark text-white p-2 rounded">
                                <strong>Juni</strong><br>
                                15 requests
                            </div> --}}
                        </div>
                    </div>
                    <!-- User 2 -->
                    <div class="leaderboard-item">
                        <div class="position-relative gradient-background">
                            <img src="{{ asset('backend/assets/images/users/6.png') }}" class="img-fluid rounded animate-up" alt="User 2" style="width: 100%; height: auto; aspect-ratio: 9/16;">
                            <div class="quarter-circle large-circle"></div>
                            <div class="quarter-circle small-circle">2</div>
                            <div class="overlay-label position-absolute">
                                <strong>Adi</strong><br>
                                12 requests
                            </div>
                            {{-- <div class="tooltip-text position-absolute bg-dark text-white p-2 rounded">
                                <strong>Adi</strong><br>
                                12 requests
                            </div> --}}
                        </div>
                    </div>
                    <!-- User 3 -->
                    <div class="leaderboard-item">
                        <div class="position-relative gradient-background">
                            <img src="{{ asset('backend/assets/images/users/7.png') }}" class="img-fluid rounded animate-up" alt="User 3" style="width: 100%; height: auto; aspect-ratio: 9/16;">
                            <div class="quarter-circle large-circle"></div>
                            <div class="quarter-circle small-circle">3</div>
                            <div class="overlay-label position-absolute">
                                <strong>Rudi</strong><br>
                                10 requests
                            </div>
                            {{-- <div class="tooltip-text position-absolute bg-dark text-white p-2 rounded">
                                <strong>Rudi</strong><br>
                                10 requests
                            </div> --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

 

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
            <table id="datatable" class="table table-bordered yajra-datatable" 
                        style="border-collapse: collapse; border-spacing: 0; width: 100%;">
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
                            @foreach($permintaans as $key => $item)
                                <tr>
                                    <td style="white-space: nowrap;">
                                        {{ $item->pilihan->first()->date ?? 'Tidak ada data' }}
                                    </td>
                                    <td style="white-space: nowrap;">
                                        {{ $item->pilihan->first()->created_by ?? 'Tidak ada data' }}
                                    </td>
                                    <td style="word-wrap: break-word; word-break: break-all; white-space: normal;">
                                        {{ $item->pilihan->first()->description ?? 'Tidak ada data' }}
                                    </td>
                                    <td class="text-center align-middle justify-content-center" style="white-space: nowrap;">
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
                                        @elseif($item->status == 'approved by admin' || $item->status == 'rejected by supervisor')
                                            <button class="btn btn-secondary bg-success text-success btn-sm font-size-13" 
                                                    style="border: 0; pointer-events: none; cursor: not-allowed;">
                                                Approved
                                            </button>
                                        @elseif($item->status == 'approved by supervisor')
                                            <button class="btn btn-secondary bg-success text-success btn-sm font-size-13" 
                                                    style="border: 0; pointer-events: none; cursor: not-allowed;">
                                                Approved
                                            </button>
                                        @endif
                                    </td>
                                    <td class="text-center align-middle justify-content-center" style="white-space: nowrap;">
                                        @if($item->status == 'approved by admin' || $item->status == 'pending')
                                            <button class="btn btn-secondary bg-warning btn-sm font-size-13" 
                                                    style="border: 0; color: #ca8a04; pointer-events: none; cursor: not-allowed;">
                                                Pending
                                            </button>
                                        @elseif($item->status == 'rejected by supervisor' || $item->status == 'rejected by admin')
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
                                                                     
                                    {{-- <td class="text-center d-flex justify-content-center align-items-center"> 
                                        @if($item->status == 'pending')
                                            <a href="{{ route('permintaan.view', $item->id) }}" class="btn btn-sm me-2 text-primary" style="width: 20px; height: 20px; padding: 0; display: flex; align-items: center; justify-content: center; text-decoration: none;">
                                                <i class="ti ti-eye font-size-20 align-middle"></i>
                                            </a>
                                            <a href="{{ route('permintaan.approve', $item->id) }}" class="btn btn-sm text-success" style="width: 20px; height: 20px; padding: 0; display: flex; align-items: center; justify-content: center; text-decoration: none;">
                                                <i class="ti ti-clipboard-check font-size-20 align-middle"></i>
                                            </a>
                                        @elseif($item->status == 'approved by admin' || $item->status == 'rejected by supervisor' || $item->status == 'rejected by admin')
                                            <a href="{{ route('permintaan.view', $item->id) }}" class="btn bg-primary btn-sm me-2 text-primary" style="width: 30px; height: 30px; padding: 0; display: flex; align-items: center; justify-content: center; text-decoration: none;">
                                                <i class="ri-eye-fill font-size-16 align-middle"></i>
                                            </a>
                                            <a href="{{ route('permintaan.approve', $item->id) }}" class="btn bg-success btn-sm" style="width: 30px; height: 30px; padding: 0; display: flex; align-items: center; justify-content: center; text-decoration: none;">
                                                <i class="fas fa-clipboard-check font-size-14 text-success align-middle"></i>
                                            </a>
                                        @elseif($item->status == 'approved by supervisor')
                                            <a href="{{ route('permintaan.view', $item->id) }}" class="btn bg-primary btn-sm me-2 text-primary" style="width: 30px; height: 30px; padding: 0; display: flex; align-items: center; justify-content: center; text-decoration: none;">
                                                <i class="ri-eye-fill font-size-16 align-middle"></i>
                                            </a>
                                            <a href="{{ route('permintaan.all', $item->id) }}" class="btn bg-danger btn-sm" style="width: 30px; height: 30px; padding: 0; display: flex; align-items: center; justify-content: center; text-decoration: none;">
                                                <i class="ri-printer-fill font-size-16 text-danger align-middle"></i>
                                            </a>
                                        @else
                                            <a href="{{ route('permintaan.view', $item->id) }}" class="btn btn-sm me-2 text-primary" style="width: 20px; height: 20px; padding: 0; display: flex; align-items: center; justify-content: center; text-decoration: none;">
                                                <i class="ti ti-eye font-size-20 align-middle"></i>
                                            </a>
                                            <a href="{{ route('permintaan.approve', $item->id) }}" class="btn btn-sm text-success" style="width: 20px; height: 20px; padding: 0; display: flex; align-items: center; justify-content: center; text-decoration: none;">
                                                <i class="ti ti-clipboard-check font-size-20 align-middle"></i>
                                            </a>
                                        @elseif($item->status == 'rejected by admin')
                                            <a href="{{ route('permintaan.view', $item->id) }}" class="btn bg-primary btn-sm" style="width: 30px; height: 30px; padding: 0; display: flex; align-items: center; justify-content: center; text-decoration: none;">
                                                <i class="ri-eye-fill align-middle text-primary"></i>
                                            </a>
                                        @endif
                                    </td> --}}
                                    <td class="text-center d-flex justify-content-center align-items-center"> 
                                        @if($item->status == 'pending')
                                            <a href="{{ route('permintaan.view', $item->id) }}" class="btn btn-sm me-2 text-primary" style="width: 20px; height: 20px; padding: 0; display: flex; align-items: center; justify-content: center; text-decoration: none;">
                                                <i class="ti ti-eye font-size-20 align-middle"></i>
                                            </a>
                                            <a href="{{ route('permintaan.approve', $item->id) }}" class="btn btn-sm text-success" style="width: 20px; height: 20px; padding: 0; display: flex; align-items: center; justify-content: center; text-decoration: none;">
                                                <i class="ti ti-clipboard-check font-size-20 align-middle"></i>
                                            </a>
                                        {{-- @elseif($item->status == 'approved by admin' || $item->status == 'rejected by supervisor' || $item->status == 'rejected by admin')
                                            <a href="{{ route('permintaan.view', $item->id) }}" class="btn bg-primary btn-sm me-2 text-primary" style="width: 30px; height: 30px; padding: 0; display: flex; align-items: center; justify-content: center; text-decoration: none;">
                                                <i class="ri-eye-fill font-size-16 align-middle"></i>
                                            </a>
                                            <a href="{{ route('permintaan.approve', $item->id) }}" class="btn bg-success btn-sm" style="width: 30px; height: 30px; padding: 0; display: flex; align-items: center; justify-content: center; text-decoration: none;">
                                                <i class="fas fa-clipboard-check font-size-14 text-success align-middle"></i>
                                            </a>
                                        @elseif($item->status == 'approved by supervisor')
                                            <a href="{{ route('permintaan.view', $item->id) }}" class="btn bg-primary btn-sm me-2 text-primary" style="width: 30px; height: 30px; padding: 0; display: flex; align-items: center; justify-content: center; text-decoration: none;">
                                                <i class="ri-eye-fill font-size-16 align-middle"></i>
                                            </a>
                                            <a href="{{ route('permintaan.all', $item->id) }}" class="btn bg-danger btn-sm" style="width: 30px; height: 30px; padding: 0; display: flex; align-items: center; justify-content: center; text-decoration: none;">
                                                <i class="ri-printer-fill font-size-16 text-danger align-middle"></i>
                                            </a> --}}
                                        @else
                                            <a href="{{ route('permintaan.view', $item->id) }}" class="btn btn-sm me-2 text-gray" style="width: 20px; height: 20px; padding: 0; display: flex; align-items: center; justify-content: center; text-decoration: none;">
                                                <i class="ti ti-eye font-size-20 align-middle"></i>
                                            </a>
                                            <a href="{{ route('permintaan.approve', $item->id) }}" class="btn btn-sm text-gray" style="width: 20px; height: 20px; padding: 0; display: flex; align-items: center; justify-content: center; text-decoration: none;">
                                                <i class="ti ti-clipboard-check font-size-20 align-middle"></i>
                                            </a>
                                        {{-- @elseif($item->status == 'rejected by admin')
                                            <a href="{{ route('permintaan.view', $item->id) }}" class="btn bg-primary btn-sm" style="width: 30px; height: 30px; padding: 0; display: flex; align-items: center; justify-content: center; text-decoration: none;">
                                                <i class="ri-eye-fill align-middle text-primary"></i>
                                            </a> --}}
                                        @endif
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
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.flash.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>

<script>
    $(document).ready(function() {
    $('#datatable').DataTable({
        initComplete: function() {
            $('#datatable thead').css('background-color', '#043277').css('color', 'white');
        }
    });
});

</script>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> <!-- Pastikan jQuery sudah di-load -->
<script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-images"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    // Initialize Bootstrap tooltips
    document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(function (element) {
        new bootstrap.Tooltip(element);
    });
</script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var barangs = @json($topBarangs);

    var labels = barangs.map(function(barang) {
        return barang.nama;
    });

    var data = barangs.map(function(barang) {
        return barang.total_qty;
    });

    var ctxBarang = document.getElementById('myChartBarang').getContext('2d');

    // Membuat gradient horizontal untuk background
    var gradientBackground = ctxBarang.createLinearGradient(0, 0, 400, 0);
    gradientBackground.addColorStop(0, 'rgba(255, 255, 255, 0.5)');  // Biru dengan opacity 0.5
    gradientBackground.addColorStop(0.5, 'rgba(0, 123, 255, 0.5)');  // Ungu dengan opacity 0.5
    gradientBackground.addColorStop(1, 'rgba(0, 123, 255, 0.7)');
    

    // Membuat gradient horizontal untuk border dengan opacity 1
    var gradientBorder = ctxBarang.createLinearGradient(0, 0, 400, 0);
    // gradientBorder.addColorStop(0, 'rgba(4, 50, 119, 1)');  // Biru dengan opacity 1
    // gradientBorder.addColorStop(1, 'rgba(111, 66, 193, 1)');  // Ungu dengan opacity 1
    gradientBorder.addColorStop(1, 'rgba(4, 50, 119, 0.5)');  // Biru dengan opacity 0.5
    gradientBorder.addColorStop(0.5, 'rgba(0, 123, 255, 0.5)');  // Ungu dengan opacity 0.5
    gradientBorder.addColorStop(0, 'rgba(0, 123, 255, 0.5)');

    var myChartBarang = new Chart(ctxBarang, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Jumlah Barang Diminta',
                data: data,
                backgroundColor: gradientBackground,
                borderColor: gradientBorder,
                borderWidth: 1,
                borderRadius: 5,
                borderSkipped: false
            }]
        },
        options: {
            indexAxis: 'y',
            scales: {
                y: {
                    beginAtZero: true,
                },
            },
            plugins: {
                tooltip: {
                    enabled: true,
                    callbacks: {
                        label: function(tooltipItem) {
                            return tooltipItem.dataset.label + ': ' + tooltipItem.formattedValue;
                        }
                    }
                }
            },
            animation: {
                duration: 2000,
                easing: 'easeInOutBounce'
            }
        }
    });
});

</script>

{{-- <script>


    document.addEventListener('DOMContentLoaded', function() {
    var barangs = @json($topBarangs);

    var labels = barangs.map(function(barang) {
        return barang.nama;
    });

    var data = barangs.map(function(barang) {
        return barang.total_qty;
    });

    var ctxBarang = document.getElementById('myChartBarang').getContext('2d');


    // Membuat gradient horizontal untuk background
    // var gradientBackground = ctxBarang.createLinearGradient(0, 0, 400, 0);
    // gradientBackground.addColorStop(0, 'rgba(4, 50, 119, 0.5)');  // Biru dengan opacity 0.2
    // gradientBackground.addColorStop(0.5, 'rgba(111, 66, 193, 0.5)');  // Ungu dengan opacity 0.2
    // gradientBackground.addColorStop(1, 'rgba(111, 66, 193, 0.5)');

    // // Membuat gradient horizontal untuk border dengan opacity 0.2
    // var gradientBorder = ctxBarang.createLinearGradient(0, 0, 400, 0);
    // gradientBorder.addColorStop(0, 'rgba(4, 50, 119, 1)');  // Biru dengan opacity 0.2
    // gradientBorder.addColorStop(1, 'rgba(111, 66, 193, 1)');  // Ungu dengan opacity 0.2
    var myChartBarang = new Chart(ctxBarang, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Jumlah Barang Diminta',
                data: data,
                backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(255, 159, 64, 0.2)'
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)'
                    ],
                borderWidth: 1,
                borderRadius: 5,
                borderSkipped: false
            }]
        },
        options: {
            indexAxis: 'y',
            scales: {
                y: {
                    beginAtZero: true,
                },
            },
            plugins: {
                tooltip: {
                    enabled: true,
                    callbacks: {
                        label: function(tooltipItem) {
                            return tooltipItem.dataset.label + ': ' + tooltipItem.formattedValue;
                        }
                    }
                }
            },
            animation: {
                duration: 2000,
                easing: 'easeInOutBounce'
            }
        }
    });
    });
</script> --}}

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Data pengguna
        var topUsers = [
            { name: 'User 1', requests: 25, img: 'https://via.placeholder.com/1080x1920?text=User+1' },
            { name: 'User 2', requests: 30, img: 'https://via.placeholder.com/1080x1920?text=User+2' },
            { name: 'User 3', requests: 22, img: 'https://via.placeholder.com/1080x1920?text=User+3' }
        ];

        var labels = topUsers.map(user => user.name);
        var data = topUsers.map(user => user.requests);
        var images = topUsers.map(user => user.img);

        var ctxUser = document.getElementById('myChartUser').getContext('2d');

        var myChartUser = new Chart(ctxUser, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Jumlah Permintaan',
                    data: data,
                    backgroundColor: 'rgba(0,0,0,0)' // Tidak digunakan
                }]
            },
            options: {
                indexAxis: 'x',
                scales: {
                    x: {
                        display: true
                        
                    },
                    y: {
                        beginAtZero: true
                         // Tampilkan label y
                    }
                },
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.label + ' - Permintaan: ' + context.raw;
                            }
                        }
                    }
                },
                animation: {
                    onComplete: function() {
                        var chart = this.chart;
                        var ctx = chart.ctx;
                        var chartArea = chart.chartArea;

                        // Draw images as bars
                        topUsers.forEach((user, index) => {
                            var x = chart.getDatasetMeta(0).data[index].x;
                            var y = chart.getDatasetMeta(0).data[index].y;
                            var width = chart.getDatasetMeta(0).data[index].width;
                            var height = chart.getDatasetMeta(0).data[index].height;

                            var img = new Image();
                            img.src = user.img;
                            img.onload = function() {
                                ctx.save();
                                ctx.translate(x - width / 2, y - height / 2); // Adjust y to center image
                                ctx.drawImage(img, 0, 0, width, height);
                                ctx.restore();
                            };
                        });
                    }
                }
            }
        });
    });
</script>
@endsection