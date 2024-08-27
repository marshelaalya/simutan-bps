@extends(auth()->user()->role === 'admin' ? 'admin.admin_master' : (auth()->user()->role === 'supervisor' ? 'supervisor.supervisor_master' : 'pegawai.pegawai_master'))
@section(auth()->user()->role === 'admin' ? 'admin' : (auth()->user()->role === 'supervisor' ? 'supervisor' : 'pegawai'))

<style>
    .table-actions {
        display: inline-flex;
        gap: 5px; /* Jarak antar tombol */
        justify-content: center;
        align-items: center;
    }

    #datatable_filter {
    justify-content: end;
}
</style>

<div class="page-content">
    <div class="container-fluid">
        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0 text-info">List Persediaan Barang</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Barang</a></li>
                            <li class="breadcrumb-item active">Persediaan Barang</li>
                        </ol>
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
                            <h3 class="card-title mb-0">Persediaan Barang</h3>
                            <a href="{{ route('barang.add') }}" class="btn btn-info waves-effect waves-light ml-3">
                                <i class="mdi mdi-plus-circle"></i> Tambah Barang
                            </a>
                        </div>

                        <div class="dropdown">
                            <button class="btn btn-info dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                BA Stock Opname
                            </button>
                            <div class="dropdown-menu p-3" aria-labelledby="dropdownMenuButton">
                                <form id="stockOpnameForm" action="{{ route('barang.export') }}" method="GET">
                                    <div class="mb-3">
                                        <label for="stockOpnameDate" class="form-label">Pilih Tanggal:</label>
                                        <input type="date" class="form-control" id="stockOpnameDate" name="tanggal" required>
                                    </div>
                                    <button type="submit" class="btn btn-info">Export</button>
                                </form>
                            </div>
                        </div>
                        
                        <table id="datatable" class="table table-bordered yajra-datatable" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead>
                                <tr>
                                    <th width="10%" class="text-center">Kode</th>
                                    <th width="20%">Kelompok Barang</th>
                                    <th>Nama Barang</th>
                                    <th width="1%" class="text-center">Stok</th>
                                    <th width="1%" class="text-center">Satuan</th>
                                    <th width="1%" class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- @foreach($barangs as $item)
                                    <tr>
                                        <td class="text-center">{{ $item->kode }}</td>
                                        <td>{{ $item->kelompok->nama ?? 'N/A' }}</td>
                                        <td>{{ $item->nama }}</td>
                                        <td class="text-center">{{ $item->qty_item }}</td>
                                        <td class="text-center">{{ $item->satuan ?? 'N/A' }}</td>
                                        <td class="table-actions">
                                            <button class="btn bg-success btn-sm add-stock-btn" data-bs-toggle="modal" data-bs-target="#addStockModal" data-id="{{ $item->id }}" data-nama="{{ $item->nama }}">
                                                <i class="fas fa-plus" style="color: #397e48"></i>
                                            </button>

                                            <a href="{{ route('barang.edit', $item->id) }}" class="btn bg-warning btn-sm">
                                                <i class="fas fa-edit" style="color: #ca8a04"></i>
                                            </a>

                                            <a href="{{ route('barang.delete', $item->id) }}" class="btn bg-danger btn-sm">
                                                <i class="fas fa-trash-alt text-danger"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach --}}
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="addStockModal" tabindex="-1" aria-labelledby="addStockModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addStockModalLabel">Tambah Stok Barang</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addStockForm">
                    <input type="hidden" name="barang_id" id="barang_id">
                    <div class="mb-3">
                        <label for="stok_qty" class="form-label">Tambah Stok</label>
                        <input type="number" class="form-control" id="stok_qty" min="1" step="1" value="1">
                    </div>
                    <button type="submit" class="btn btn-info">Tambah Stok</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Include DataTables JS and Buttons JS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.flash.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.5/xlsx.full.min.js"></script>



<script>
    $(document).ready(function() {
    $('[data-tooltip]').tooltip();
});

    $(document).ready(function() {
        var table = $('.yajra-datatable').DataTable({
            // processing: true,
            serverSide: true,
            responsive: true,
            ajax: {
                url: "{{ route('barang.allAct') }}",  // Pastikan route ini sesuai dengan yang ada di web.php
                data: function(d) {
                    d.kelompok_id = $('#kelompok_filter').val();  // Filter berdasarkan kelompok barang
                }
            },
            columns: [
                { data: 'kode', name: 'kode', className: 'text-center' },
                { data: 'kelompok_barang', name: 'kelompok_barang' },
                { data: 'nama', name: 'nama' },
                { data: 'qty_item', name: 'qty_item', className: 'text-center' },
                { data: 'satuan', name: 'satuan', className: 'text-center' },
                { data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center' }
            ],
            dom: 'Bfrtip',
            buttons: [
                        {
                            extend: 'collection',
                            text: 'BA Stock Opname',
                            buttons: [
                                {
                                    text: 'Pilih Tanggal',
                                    action: function (e, dt, node, config) {
                                        $('#dropdownMenuButton').dropdown('toggle');
                                    }
                                }
                            ]
                        },
                        {
                            text: 'Laporan Rincian Persediaan',
                            action: function (e, dt, node, config) {
                                window.location.href = "{{ route('barang.pemasukan.export') }}"; // Laravel route for exporting Excel
                            }
                        }
            ],
            initComplete: function() {
                var kelompokSelect = $('<select id="kelompok_filter" class="form-select" style="width: 33%;"><option value="">Semua Kelompok Barang</option></select>')
                    .appendTo($('#datatable_filter').css('display', 'flex').css('align-items', 'center').css('gap', '10px'))
                    .on('change', function() {
                        table.draw();
                    });

                // Menambahkan opsi untuk kelompok barang dari server (opsional, jika ingin dinamis)
                @foreach($kelompokFilt as $kelompok)
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

                $('.form-control').each(function() {
                    $(this).css({
                        'margin-bottom': '0px',
                        'height': '2.38rem',
                    });
                });

                $('label').each(function() {
                    $(this).css({
                        'margin-bottom': '0px',
                        'height': '2.38rem',
                        'font-weight': '600',
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

        $(document).ready(function() {
            $('.dt-button').removeClass('dt-button buttons-collection');
            $('.dt-button-background').remove(); // Hapus semua elemen dengan class .dt-button-background
            $('.dt-button-down-arrow').remove(); // Hapus semua elemen dengan class .dt-button-down-arrow
            $('.form-control').removeClass('form-control-sm');
        });
            

        // Handle the Add Stock button click
        $('#datatable').on('click', '.add-stock-btn', function() {
            var barangId = $(this).data('id');
            var barangNama = $(this).data('nama');

            $('#barang_id').val(barangId);
            $('#addStockModalLabel').text('Tambah Stok Barang: ' + barangNama);
        });

        // Handle form submission
        $('#addStockForm').on('submit', function(e) {
            e.preventDefault();
            var barangId = $('#barang_id').val();
            var qty = $('#stok_qty').val();

            $.ajax({
                url: '{{ route('barang.addStock') }}',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    barang_id: barangId,
                    qty: qty
                },
                success: function(response) {
                    $('#addStockModal').modal('hide');
                    
                    // Show success notification
                    toastr.success('Stok berhasil ditambahkan!', 'Berhasil');

                    // Refresh halaman
                    location.reload();
                },
                error: function(xhr, status, error) {
                    // Show error notification
                    toastr.error('Gagal menambahkan stok. Coba lagi.', 'Error');
                }
            });
        });
    });
</script>


@endsection