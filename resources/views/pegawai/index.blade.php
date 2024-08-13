@extends(auth()->user()->role === 'admin' ? 'admin.admin_master' : 
         (auth()->user()->role === 'supervisor' ? 'supervisor.supervisor_master' : 
         'pegawai.pegawai_master'))

@section(auth()->user()->role === 'admin' ? 'admin' : 
         (auth()->user()->role === 'supervisor' ? 'supervisor' : 'pegawai'))


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
    width: calc(100% - 6rem); /* Reduce container width to fit within buttons */
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
    font-size: 16px;
    margin: 10px 0;
}

.card-slider-text {
    font-size: 14px;
}

.swiper-pagination-bullet {
    background: #007bff; /* Customize pagination bullet color */
}

.swiper-pagination-bullet-active {
    background: #0056b3; /* Active bullet color */
}

.swiper-container-wrapper {
        position: relative;
        display: none; /* Hide all Swiper containers by default */
    }
    .swiper-container-wrapper.active {
        position: relative;
        display: block; /* Show the active Swiper container */
    }

    .kelompok-button {
        cursor: pointer;
        padding: 10px 20px;
        margin: 5px;
        background-color: rgba(15, 156, 243, 0.5);
        color: white;
        border: none;
        border-radius: 5px;
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

        
        <!-- Buttons for Kelompok -->
        <div class="mb-4">
            @foreach($kelompoks as $index => $kelompok)
            <button class="kelompok-button {{ $kelompok->id === $kelompokWithMostBarangs->id ? 'active' : '' }} fw-bold" data-target="#swiper-container-{{ $index }}">
                {{ $kelompok->nama }}
            </button>
            @endforeach
        </div>
        <!-- Sliders for Each Kelompok Barang -->
        @foreach($kelompoks as $index => $kelompok)
        <div class="swiper-container-wrapper mb-4 {{ $kelompok->id === $kelompokWithMostBarangs->id ? 'active' : '' }}" id="swiper-container-{{ $index }}">
            <div class="swiper-container">
                <div class="swiper-wrapper">
                    @foreach($kelompok->barangs as $item)
                        <div class="swiper-slide">
                            <div class="card-slider">
                                <img src="https://cdn-icons-png.flaticon.com/128/2659/2659360.png" class="card-slider-img-top" alt="Gambar Barang"> 
                                <div class="card-slider-body">
                                    <h5 class="card-slider-title">{{ $item->nama }}</h5>
                                    <p class="card-slider-text">Stok: {{ $item->qty_item }} {{ $item->satuan->nama ?? 'N/A' }}</p>
                                    {{-- <p class="card-slider-text">{{ $item->satuan->nama ?? 'N/A' }}</p> --}}
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

    {{-- <div class="row gx-4">
        <div class="col-xl-3 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="flex-grow-1">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> --}}
    <!-- Chart Container -->
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

    <div class="row gx-4">
            <div class="col-xl-3 col-md-6">
                <div class="card">
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
            <div class="col-xl-3 col-md-6">
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
            <div class="col-xl-3 col-md-6">
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
            <div class="col-xl-3 col-md-6">
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


        <div class="row">
            <div class="col-xl-12">
                <h4 class="card-title mb-3 text-info">Stok Barang</h4>
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="datatable" class="table table-bordered dt-responsive nowrap" 
                        style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            
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
                                @foreach($barangs as $item)
                                    <tr>
                                        <td width="1%" class="text-center">{{ $item->kode }}</td>
                                        <td width="20%">{{ $item->kelompok->nama ?? 'N/A' }}</td>
                                        <td>{{ $item->nama }}</td>
                                        <td width="1%" class="text-center">{{ $item->qty_item }}</td>
                                        <td width="1%" class="text-center">{{ $item->satuan->nama ?? 'N/A' }}</td>
                                        
                                            {{-- <a href="{{ route('barang.edit', $item->id) }}" class="btn btn-info sm" title="Edit Data"> <i class="fas fa-edit"></i> </a>
                                            <a href="{{ route('barang.delete', $item->id) }}" class="btn btn-danger sm" title="Delete Data" id="delete"> <i class="fas fa-trash-alt"></i> </a> --}}
                                        
                                        {{-- <td class="table-actions" style="text-align: center; vertical-align: middle;">
                                            <!-- Tombol dengan link route ke halaman view -->
                                            <a href="{{ route('barang.edit', $item->id) }}" class="btn bg-warning btn-sm">
                                                <i class="fas fa-edit" style="color: #ca8a04"></i>
                                            </a>
                                            
                                            <!-- Tombol dengan link route ke halaman print -->
                                            <a href="{{ route('barang.delete', $item->id) }}" class="btn bg-danger btn-sm">
                                                <i class="fas fa-trash-alt text-danger"></i>
                                            </a>
                                        </td> --}}
                                        
                                    </tr>
                                @endforeach
                            </tbody>

                        </table>
                        </div>
                        <div class="d-flex justify-content-end fw-bold">
                            <a href="{{ route('permintaan.all') }}" class="text-info">Lihat Selengkapnya <i class="mdi mdi-arrow-right font-size-16 text-info align-middle"></i></a>
                        </div>                
                    </div><!-- end card -->
                </div><!-- end card -->
            </div><!-- end col -->
        </div><!-- end row -->

    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> <!-- Pastikan jQuery sudah di-load -->
<script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>

<script>
    $(document).ready(function() {
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



<script>
    $(document).ready(function() {
        // Initialize Swipers
        var swipers = [];
        $('.swiper-container').each(function() {
            swipers.push(new Swiper(this, {
                loop: true,
                slidesPerView: 6,
                spaceBetween: 20,
                pagination: {
                    el: '.swiper-pagination',
                    clickable: true,
                },
                navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev',
                },
                autoplay: {
                    delay: 3000,
                    disableOnInteraction: false,
                },
                breakpoints: {
                    640: {
                        slidesPerView: 2,
                    },
                    768: {
                        slidesPerView: 4,
                    },
                    1024: {
                        slidesPerView: 6,
                    },
                }
            }));
        });

        // Handle button clicks
        $('.kelompok-button').click(function() {
            var target = $(this).data('target');

            // Fade out all Swiper containers
            $('.swiper-container-wrapper').fadeOut(300, function() {
                // After fading out, hide all containers
                $(this).removeClass('active').css('display', 'none');
            });

            // Show the targeted Swiper container
            $(target).fadeIn(300, function() {
                $(this).addClass('active').css('display', 'block');
            });

            // Mark the clicked button as active
            $('.kelompok-button').removeClass('active');
            $(this).addClass('active');
        });

        // Show the Swiper container with the most barangs by default
        if ($('.kelompok-button').length) {
            $('.kelompok-button.active').click();
        }
    });
</script>



@endsection