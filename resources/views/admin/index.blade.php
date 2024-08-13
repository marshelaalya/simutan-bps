@extends(auth()->user()->role === 'admin' ? 'admin.admin_master' : 
         (auth()->user()->role === 'supervisor' ? 'supervisor.supervisor_master' : 
         'pegawai.pegawai_master'))

@section(auth()->user()->role === 'admin' ? 'admin' : 
         (auth()->user()->role === 'supervisor' ? 'supervisor' : 'pegawai'))

{{-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"> --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

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

<div class="row gx-4">
    <!-- Chart for Top Score Barang Diminta -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-3 text-info">Top Score Barang Diminta</h4>
                <canvas id="myChartBarang" width="400" height="200" style="max-width: 100%;"></canvas>
            </div>
        </div>
    </div>

    <!-- Chart for Top Score Request User -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-3 text-info">Top Score Request User</h4>
                <canvas id="myChartUser" width="400" height="200" style="max-width: 100%;"></canvas>
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
                                                                     
                                    <td class="text-center d-flex justify-content-center align-items-center"> 
                                        @if($item->status == 'pending')
                                            <a href="{{ route('permintaan.view', $item->id) }}" class="btn bg-primary btn-sm me-2 text-primary" style="width: 30px; height: 30px; padding: 0; display: flex; align-items: center; justify-content: center; text-decoration: none;">
                                                <i class="ri-eye-fill font-size-16 align-middle"></i>
                                            </a>
                                            <a href="{{ route('permintaan.approve', $item->id) }}" class="btn bg-success btn-sm" style="width: 30px; height: 30px; padding: 0; display: flex; align-items: center; justify-content: center; text-decoration: none;">
                                                <i class="fas fa-clipboard-check font-size-14 text-success align-middle"></i>
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

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize the first chart
        var ctxBarang = document.getElementById('myChartBarang').getContext('2d');
        var myChartBarang = new Chart(ctxBarang, {
            type: 'bar', // or 'line', 'pie', etc.
            data: {
                labels: ['Pensil', 'Pulpen', 'Sapu', 'Kertas', 'Penghapus', 'Lampu'], // X-axis labels
                datasets: [{
                    label: 'Top Score Barang Diminta',
                    data: [12, 19, 3, 5, 2, 3], // Data points
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
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // Initialize the second chart
        var ctxUser = document.getElementById('myChartUser').getContext('2d');
        var myChartUser = new Chart(ctxUser, {
            type: 'bar', // or 'bar', 'pie', etc.
            data: {
                labels: ['Rudi', 'Juni', 'Ulul', 'Budi', 'Lia', 'Mona'], // X-axis labels
                datasets: [{
                    label: 'Top Score Request User',
                    data: [5, 15, 10, 25, 7, 10], // Data points
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
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    });
</script>

@endsection