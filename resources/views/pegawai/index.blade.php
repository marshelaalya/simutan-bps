@extends(auth()->user()->role === 'admin' ? 'admin.admin_master' : 
         (auth()->user()->role === 'supervisor' ? 'supervisor.supervisor_master' : 
         'pegawai.pegawai_master'))

@section(auth()->user()->role === 'admin' ? 'admin' : 
         (auth()->user()->role === 'supervisor' ? 'supervisor' : 'pegawai'))

<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">

<style>
    .swiper-horizontal .swiper-pagination-bullets,
.swiper-pagination-bullets.swiper-pagination-horizontal,
.swiper-pagination-custom,
.swiper-pagination-fraction {
  bottom: -15px; /* Ganti dengan nilai yang diinginkan */
}

    .swiper-button-next, .swiper-button-prev {
    color: #043277; /* Customize navigation button color */
    font-weight:bold;
    --swiper-navigation-size: 20px;
    top: 50%; /* Center vertically */
    transform: translateY(-50%);
    z-index: 10; /* Ensure buttons are above cards */
    width: 30px; /* Adjust button width */
    height: 30px; /* Adjust button height */
    background-color: rgba(255, 255, 255, 0.8); /* Add semi-transparent background */
    border-radius: 50%; /* Make buttons round */
    display: flex;
    justify-content: center;
    align-items: center;
    position: absolute; /* Position them absolutely */
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); /* Add shadow */
}

.swiper-pagination{
    position: relative;
}

.swiper-button-next {
    right: 10px; /* Adjust right button distance */
}

.swiper-button-prev {
    left: 10px; /* Adjust left button distance */
}

.swiper-container {
    width: calc(100% - 7rem); Reduce container width to fit within buttons
    height: fit-content; /* Adjust slider height */
    position: relative;
    overflow: hidden; /* Ensure no elements overflow */
    margin: 0 auto; /* Center the container */
    padding-bottom: 20px;
}

.swiper-slide {
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 10px;
}

.card-slider {
    width: 180px; /* Adjust card width */
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); /* Add shadow */
    border-radius: 8px; /* Rounded corners */
    overflow: hidden;
    background: #fff; /* Background color */
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    z-index: 5;
}

.card-slider:hover {
    transform: scale(1.05); /* Zoom effect on hover */
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3);
}

.card-slider-body {
    padding: 15px;
    text-align: center;
    display: flex;
    flex-direction: column;
    justify-content: center;
}

.card-slider-img-top {
    width: 100%;
    /* height: 120px; */
    object-fit: cover;
    padding: 15px 15px 0 15px;
}

.card-slider-title {
    font-size: 14px;
    margin: 10px 0;
}

.card-slider-text {
    font-size: 12px;
}

.swiper-pagination-bullet {
    background: white; /* Customize pagination bullet color */
}

.swiper-pagination-bullet-active {
    background: #fff; /* Active bullet color */
}

.swiper-container-wrapper {
        position: relative;
        display: none; /* Hide all Swiper containers by default */
        z-index: 10;
    }
    .swiper-container-wrapper.active {
        position: relative;
        display: block; /* Show the active Swiper container */
    }

    .kelompok-button {
        cursor: pointer;
        padding: 10px 20px;
        margin: 0 5px 0 0 ;
        background-color: rgba(4, 127, 209, 0.3);
        color: #fff;
        font-weight: bold;
        border: none;
        border-radius: 5px 5px 0 0;
        font-size: 16px;
    }

    .kelompok-button.active {
        background-color: #043277;
    }

    .col-md-6 {
        margin-bottom: 1.5rem; /* Space between columns */
    }

    canvas {
        width: 100% !important; /* Ensure canvas fits its container */
        height: auto !important; /* Adjust height based on width */
    }

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

    .large-circle2 {
        width: 180px;
        height: 180px;
        top: 0;
        right: 0;
    }

    .small-circle2 {
        width: 160px;
        height: 160px;
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

    .card, .gradient-background2{
        border-radius: 0.375rem;
        overflow:hidden;
    }
    .gradient-background::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(to bottom, rgba(0, 123, 255, 1), rgba(0, 123, 255, 0) 100%);
        z-index: 1;
        pointer-events: none;
        border-radius: 0.375rem;
    }

    .gradient-background2::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: conic-gradient(from 90deg at bottom right, rgba(4, 127, 209, 0.6), #043277);
        z-index: 1;
        pointer-events: none;
        border-radius: 0.375rem;
    }

    #datatable_filter {
    justify-content: end;
}

    .gradient-background3::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(to bottom, rgba(65,130,255,1) 0%, rgba(181,212,248,1) 100%);
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

    .card, .gradient-background, .gradient-background2, .gradient-background3 {
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

.swiper-wrapper {
    z-index: 10;
}

.swiper-container-wrapper,
    .kelompok-definisi {
        display: none;
        opacity: 0;
        transition: opacity 0.5s ease-in-out;
        position: relative; /* Tambahkan untuk menghindari pergeseran vertikal */
    }

    .swiper-container-wrapper.active,
    .kelompok-definisi.active {
        display: block;
        opacity: 1;
    }

    .table-actions {
        display: inline-flex;
        gap: 5px; /* Jarak antar tombol */
        justify-content: center;
        align-items: center;
    }
</style>

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

        <div>
            @foreach($kelompoks as $index => $kelompok)
            <button class="kelompok-button {{ $kelompok->id === $kelompokWithMostBarangs->id ? 'active' : '' }} fw-bold" data-target="#swiper-container-{{ $index }}" data-definisi="#definisi-{{ $index }}">
                {{ $kelompok->nama }}
            </button>
            @endforeach
        </div>
        <div class="card gradient-background2 mb-4" style="border-radius: 0 0.25rem 0.25rem 0.25rem; overflow: hidden; margin: 0; padding: 0; position: relative;">
            <!-- SVG Wave -->
            <div class="svg-wave" style="position: absolute; top: 0; left: 0; width: 100%; height: auto; z-index: 1;">
                <svg viewBox="0 0 500 200" preserveAspectRatio="none">
                    <path d="M0,80 C120,130 180,30 280,90 C400,160 500,50 500,50 L500,0 L0,0 Z" fill="#043277" opacity="0.7"></path> <!-- Warna Ungu -->
                    <path d="M0,100 C150,150 250,20 350,110 C450,200 500,60 500,60 L500,0 L0,0 Z" fill="#043277" opacity="0.7"></path> <!-- Warna Biru -->
                    <path d="M0,120 C180,170 300,50 400,130 C480,200 500,70 500,70 L500,0 L0,0 Z" fill="#043277" opacity="0.5"></path> <!-- Warna Biru Muda -->
                </svg> 
            </div>
        
            <div class="row gx-4" style="position: relative; z-index: 2;">
                <!-- Chart for Top Score Barang Diminta (Kolom Kiri) -->
                <div style="flex:0 0 auto; width:40%">
                    <div class="card mb-0" style="background-color: rgba(4, 50, 119, 0); box-shadow:none">
                        <div class="card-body">
                            @foreach($kelompoks as $index => $kelompok)
                            <div class="kelompok-definisi {{ $kelompok->id === $kelompokWithMostBarangs->id ? 'active' : '' }}" id="definisi-{{ $index }}" style="display: {{ $kelompok->id === $kelompokWithMostBarangs->id ? 'block' : 'none' }};">
                                <h4 class="mb-2" style="color: white; ">{{ $kelompok->nama }}</h4>
                                <p style="color: white; font-size: 18px; font-weight:400">
                                    @if($kelompok->nama === 'Barang Pemeliharaan')
                                        Barang pemeliharaan adalah barang-barang yang digunakan untuk pemeliharaan dan perawatan fasilitas, gedung, dan peralatan kantor agar tetap berfungsi dengan baik.
                                    @elseif($kelompok->nama === 'Barang Konsumsi')
                                        Barang konsumsi adalah barang-barang yang habis pakai dalam jangka waktu tertentu dan perlu diganti secara rutin, seperti kertas, tinta, dan alat tulis lainnya.
                                    @elseif($kelompok->nama === 'Alat Kegiatan Kantor Lainnya')
                                        Alat kegiatan kantor mencakup peralatan yang digunakan untuk mendukung berbagai kegiatan operasional kantor, seperti komputer, printer, mesin fotokopi, dan peralatan presentasi.
                                    @else
                                        Definisi untuk kelompok ini belum tersedia.
                                    @endif
                                </p>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
        
                <!-- Sliders for Each Kelompok Barang (Kolom Kanan) -->
                <div style="flex:0 0 auto; width:60%">
                    <div class="card mb-0" style="background-color: rgba(4, 50, 119, 0); box-shadow:none">
                        <div class="card-body">
                            @foreach($kelompoks as $index => $kelompok)
                                <div class="swiper-container-wrapper {{ $kelompok->id === $kelompokWithMostBarangs->id ? 'active' : '' }}" id="swiper-container-{{ $index }}">
                                    <div class="swiper-container">
                                        <div class="swiper-wrapper">
                                            @foreach($kelompok->barangs as $item)
                                                <div class="swiper-slide">
                                                    <div class="card-slider">
                                                        <img src="https://cdn-icons-png.flaticon.com/128/2659/2659360.png" class="card-slider-img-top" alt="Gambar Barang"> 
                                                        <div class="card-slider-body">
                                                            <h5 class="card-slider-title">{{ $item->nama }}</h5>
                                                            <p class="card-slider-text">Stok: {{ $item->qty_item }} {{ $item->satuan->nama ?? 'N/A' }}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
        
                                        <!-- Add Pagination -->
                                        <div class="swiper-pagination"></div>
                                    </div>
        
                                    <!-- Add Navigation -->
                                    <div class="swiper-button-next"></div>
                                    <div class="swiper-button-prev"></div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row gx-4">
                <div class="col-xl-3 col-md-6 mb-0">
                    <div class="card mb-0">
                        <div class="card-body">
                            <div class="d-flex">
                                <div class="flex-grow-1">
                                    <h5 class="mb-1">Total Permintaan<br>Diajukan</h5>
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
                <div class="col-xl-3 col-md-6 mb-0">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex">
                                <div class="flex-grow-1">
                                    <h5 class="mb-1">Total Permintaan<br>Selesai</h5>
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
                <div class="col-xl-3 col-md-6 mb-0">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex">
                                <div class="flex-grow-1">
                                    <h5 class="mb-1">Total Permintaan<br>Pending</h5>
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
                <div class="col-xl-3 col-md-6 mb-0">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex">
                                <div class="flex-grow-1">
                                    <h5 class="mb-1">Total Permintaan<br>Ditolak</h5>
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

            <!-- Chart Container -->
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
        
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title mb-3 text-info" style="padding-bottom: 25px;">Top Score Request User</h4>
                        <div class="leaderboard-container" style="position: relative;">
                            <!-- User 1 -->
                            <div class="leaderboard-item" style="border-radius: 0.375rem; overflow: hidden; position: relative; background: linear-gradient(to top right, #3671ac 30%, rgba(54, 113, 172, 0.608) 100%);">
                                <div class="svg-wave" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; z-index: 2;">
                                    <svg viewBox="0 0 500 150" preserveAspectRatio="none" style="width: 100%; height: 100%;">
                                        <!-- Wave 1 -->
                                        <path d="M 0 70 C 150 40 350 60 500 40 L 500 0 L 0 0 Z" fill="#043277" opacity="0.4"></path>
                                        <!-- Wave 2 -->
                                        <path d="M 0 60 C 150 30 350 50 500 30 L 500 0 L 0 0 Z" fill="#043277" opacity="0.6"></path>
                                        <!-- Wave 3 -->
                                        <path d="M 0 50 C 150 20 350 40 500 20 L 500 0 L 0 0 Z" fill="#043277" opacity="0.1"></path>
                                        <!-- Wave 4 -->
                                        <path d="M 0 40 C 150 10 350 30 500 10 L 500 0 L 0 0 Z" fill="#043277"></path>
                                    </svg>
                                    
                                </div>
                                <div class="position-relative" style="border-radius: 0 0.25rem 0.25rem 0.25rem; overflow: hidden; margin: 0; padding: 0; position: relative;">
                                    <img src="{{ asset('backend/assets/images/users/16.png') }}" class="img-fluid rounded animate-up" alt="User 1" style="width: 100%; height: auto; aspect-ratio: 9/16; z-index: 2; position: relative;">
                                    <div class="quarter-circle large-circle"></div>
                                    <div class="quarter-circle small-circle">1</div>
                                    <div class="overlay-label position-absolute">
                                        <strong>Juni</strong><br>
                                        15 requests
                                    </div>
                                </div>
                            </div>
                            
            
                            <!-- User 2 -->
                            <div class="leaderboard-item" style="border-radius: 0.375rem; overflow: hidden; position: relative; background: linear-gradient(to top right, #3671ac 30%, rgba(54, 113, 172, 0.608) 100%);">
                                <div class="svg-wave" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; z-index: 2;">
                                    <svg viewBox="0 0 500 150" preserveAspectRatio="none" style="width: 100%; height: 100%;">
                                        <!-- Wave 1 -->
                                        <path d="M 0 70 C 150 40 350 60 500 40 L 500 0 L 0 0 Z" fill="#043277" opacity="0.4"></path>
                                        <!-- Wave 2 -->
                                        <path d="M 0 60 C 150 30 350 50 500 30 L 500 0 L 0 0 Z" fill="#043277" opacity="0.6"></path>
                                        <!-- Wave 3 -->
                                        <path d="M 0 50 C 150 20 350 40 500 20 L 500 0 L 0 0 Z" fill="#043277" opacity="0.1"></path>
                                        <!-- Wave 4 -->
                                        <path d="M 0 40 C 150 10 350 30 500 10 L 500 0 L 0 0 Z" fill="#043277"></path>
                                    </svg>
                                    
                                </div>
                                <div class="position-relative" style="border-radius: 0 0.25rem 0.25rem 0.25rem; overflow: hidden; margin: 0; padding: 0; position: relative;">
                                    <img src="{{ asset('backend/assets/images/users/6.png') }}" class="img-fluid rounded animate-up" alt="User 2" style="width: 100%; height: auto; aspect-ratio: 9/16;">
                                    <div class="quarter-circle large-circle"></div>
                                    <div class="quarter-circle small-circle">2</div>
                                    <div class="overlay-label position-absolute" >
                                        <strong>Adi</strong><br>
                                        12 requests
                                    </div>
                                </div>
                            </div>
            
                            <!-- User 3 -->
                            <div class="leaderboard-item" style="border-radius: 0.375rem; overflow: hidden; position: relative; background: linear-gradient(to top right, #3671ac 30%, rgba(54, 113, 172, 0.608) 100%);">
                                <div class="svg-wave" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; z-index: 2;">
                                    <svg viewBox="0 0 500 150" preserveAspectRatio="none" style="width: 100%; height: 100%;">
                                        <!-- Wave 1 -->
                                        <path d="M 0 70 C 150 40 350 60 500 40 L 500 0 L 0 0 Z" fill="#043277" opacity="0.4"></path>
                                        <!-- Wave 2 -->
                                        <path d="M 0 60 C 150 30 350 50 500 30 L 500 0 L 0 0 Z" fill="#043277" opacity="0.6"></path>
                                        <!-- Wave 3 -->
                                        <path d="M 0 50 C 150 20 350 40 500 20 L 500 0 L 0 0 Z" fill="#043277" opacity="0.1"></path>
                                        <!-- Wave 4 -->
                                        <path d="M 0 40 C 150 10 350 30 500 10 L 500 0 L 0 0 Z" fill="#043277"></path>
                                    </svg>
                                    
                                </div>
                                <div class="position-relative" style="border-radius: 0 0.25rem 0.25rem 0.25rem; overflow: hidden; margin: 0; padding: 0; position: relative;">
                                    <img src="{{ asset('backend/assets/images/users/7.png') }}" class="img-fluid rounded animate-up" alt="User 3" style="width: 100%; height: auto; aspect-ratio: 9/16;">
                                    <div class="quarter-circle large-circle"></div>
                                    <div class="quarter-circle small-circle">3</div>
                                    <div class="overlay-label position-absolute">
                                        <strong>Rudi</strong><br>
                                        10 requests
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>   
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <h4 class="card-title mb-0">Stok Barang</h4>
                        </div>
                        <table id="datatable" class="table table-bordered yajra-datatable" style="border-collapse: collapse; border-spacing: 0; width: 100%;">            
                            <thead>
                                <tr>
                                    <th width="10%" class="text-center">Kode</th>
                                    <th width="20%">Kelompok Barang</th>
                                    <th>Nama Barang</th>
                                    <th width="1%" class="text-center">Stok</th>
                                    <th width="1%" class="text-center">Satuan</th>
                                    {{-- <th width="1%" class="text-center">Aksi</th> --}}
                                </tr>
                            </thead>
                            <tbody>  
                            </tbody>
                        </table>

                        <div class="d-flex justify-content-end fw-bold">
                            <a href="{{ route('permintaan.all') }}" class="text-info">Lihat Selengkapnya <i class="mdi mdi-arrow-right font-size-16 text-info align-middle"></i></a>
                        </div>                
                    </div><!-- end card -->
                </div><!-- end card -->
            </div><!-- end col -->
        </div><!-- end row -->
    </div>
</div>

 <!-- Pastikan jQuery sudah di-load -->
<script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-images"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.flash.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>

<script type="text/javascript">
    $(document).ready(function() {
        var table = $('.yajra-datatable').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            ajax: {
                url: "{{ route('barang.all') }}",  // Pastikan route ini sesuai dengan yang ada di web.php
                data: function(d) {
                    d.kelompok_id = $('#kelompok_filter').val();  // Filter berdasarkan kelompok barang
                }
            },
            columns: [
                { data: 'kode', name: 'kode', className: 'text-center align-content-center' },
                { data: 'kelompok.nama', name: 'kelompok.nama', className: 'align-content-center' },
                { data: 'nama', name: 'nama', className: 'align-content-center' },
                { data: 'qty_item', name: 'qty_item', className: 'text-center align-content-center' },
                { data: 'satuan', name: 'satuan.nama', className: 'text-center align-content-center' }
            ],
            dom: 'Brftip',
            buttons: [
                {
                    extend: 'collection',
                    text: 'Export &nbsp',
                    className: 'form-select',
                    buttons: [
                        {
                            extend: 'excelHtml5',
                            text: 'Export Excel',
                            title: 'Data Barang',
                            exportOptions: {
                                columns: ':not(.no-export)' // Eksklusi kolom dengan kelas 'no-export'
                            },
                        },
                        {
                            extend: 'copy',
                            text: 'Copy',
                            exportOptions: {
                                columns: ':not(.no-export)' // Eksklusi kolom dengan kelas 'no-export'
                            },
                        },
                        {
                            extend: 'csv',
                            text: 'CSV',
                            exportOptions: {
                                columns: ':not(.no-export)' // Eksklusi kolom dengan kelas 'no-export'
                            },
                        },
                        {
                            extend: 'pdf',
                            text: 'PDF',
                            exportOptions: {
                                columns: ':not(.no-export)' // Eksklusi kolom dengan kelas 'no-export'
                            },
                        },
                        {
                            extend: 'print',
                            text: 'Print',
                            exportOptions: {
                                columns: ':not(.no-export)' // Eksklusi kolom dengan kelas 'no-export'
                            },
                        }
                    ]
                }
            ],
            initComplete: function() {
                // Filter untuk kelompok barang
                var kelompokSelect = $('<select id="kelompok_filter" class="form-select" style="width: 33%;"><option value="">Semua Kelompok Barang</option></select>')
                    .appendTo($('#datatable_filter').css('display', 'flex').css('align-items', 'center').css('gap', '10px'))
                    .on('change', function() {
                        table.draw();
                    });

                // Menambahkan opsi untuk kelompok barang dari server (opsional, jika ingin dinamis)
                @foreach($kelompoks as $kelompok)
                    kelompokSelect.append('<option value="{{ $kelompok->id }}">{{ $kelompok->nama }}</option>');
                @endforeach
    
                // Styling untuk select
                $('.form-select').each(function() {
                    $(this).css({
                        'display': 'block',
                        'padding': '.47rem 1.75rem .47rem .75rem',
                        '-moz-padding-start': 'calc(.75rem - 3px)',
                        'font-size': '.9rem',
                        'font-weight': '500',
                        'line-height': '1.5',
                        'color': '#505d69',
                        'background-color': '#fff',
                        'background-image': 'url("data:image/svg+xml,%3csvg xmlns=\'http://www.w3.org/2000/svg\' viewBox=\'0 0 16 16\'%3e%3cpath fill=\'none\' stroke=\'%230a1832\' stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M2 5l6 6 6-6\'/%3e%3c/svg%3e")',
                        'background-repeat': 'no-repeat',
                        'background-position': 'right .75rem center',
                        'background-size': '16px 12px',
                        'border': '1px solid #ced4da',
                        'border-radius': '.25rem',
                        'transition': 'border-color .15s ease-in-out, box-shadow .15s ease-in-out',
                        'appearance': 'none'
                    });
                });
    
                var observer = new MutationObserver(function(mutations) {
                    mutations.forEach(function(mutation) {
                        $('.dt-button-background').remove(); // Hapus elemen dengan class .dt-button-background
                    });
                });
    
                // Memulai observer pada elemen yang mengandung tombol
                observer.observe(document.body, {
                    childList: true,
                    subtree: true
                });
            }
        });

        $(document).ajaxComplete(function() {
            // Pastikan elemen sudah ada sebelum mencoba menghapusnya
            setTimeout(function() {
                $('.dt-button').removeClass('dt-button buttons-collection');
                $('.dt-button-background').remove(); // Hapus semua elemen dengan class .dt-button-background
                $('.dt-button-down-arrow').remove(); // Hapus semua elemen dengan class .dt-button-down-arrow
            }, 100); // Menunggu beberapa waktu sebelum menghapus
        });
    });
</script>



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
    gradientBackground.addColorStop(0.3, 'rgba(54, 113, 172, 0.5)');  // Ungu dengan opacity 0.5
    gradientBackground.addColorStop(1, 'rgba(4, 50, 119, 1)');
    

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



<script>
    $(document).ready(function() {
        // Initialize Swipers
        var swipers = [];
        $('.swiper-container').each(function() {
            swipers.push(new Swiper(this, {
                loop: true,
                slidesPerView: 3,
                spaceBetween: 10,
                pagination: {
                    el: $(this).find('.swiper-pagination')[0],
                    clickable: true,
                },
                navigation: {
                    nextEl: $(this).find('.swiper-button-next')[0],
                    prevEl: $(this).find('.swiper-button-prev')[0],
                },
                autoplay: {
                    delay: 3000,
                    disableOnInteraction: false,
                },
                breakpoints: {
                    640: {
                        slidesPerView: 1,
                    },
                    768: {
                        slidesPerView: 2,
                    },
                    1024: {
                        slidesPerView: 3,
                    },
                }
            }));
        });

        // Handle button clicks
        $('.kelompok-button').click(function() {
            var target = $(this).data('target');
            var definisiTarget = $(this).data('definisi');

            // Fade out all Swiper containers and definisi
            $('.swiper-container-wrapper.active, .kelompok-definisi.active').removeClass('active').css('display', 'none');

            // Show the targeted Swiper container and definisi
            $(target).css('display', 'block').addClass('active');
            $(definisiTarget).css('display', 'block').addClass('active');

            // Mark the clicked button as active
            $('.kelompok-button').removeClass('active');
            $(this).addClass('active');
        });

        // Show the Swiper container and definisi with the most barangs by default
        if ($('.kelompok-button').length) {
            $('.kelompok-button.active').click();
        }
    });
</script>


@endsection