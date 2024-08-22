@extends(auth()->user()->role === 'admin' ? 'admin.admin_master' : (auth()->user()->role === 'supervisor' ? 'supervisor.supervisor_master' : 'pegawai.pegawai_master'))
@section(auth()->user()->role === 'admin' ? 'admin' : (auth()->user()->role === 'supervisor' ? 'supervisor' : 'pegawai'))

<style>
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
                        
                        <table id="datatable" class="table table-bordered yajra-datatable" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead>
                                <tr>
                                    <th width="10%" class="text-center">Kode</th>
                                    <th width="20%">Kelompok Barang</th>
                                    <th>Nama Barang</th>
                                    <th width="1%" class="text-center">Stok</th>
                                    <th width="1%" class="text-center">Satuan</th>
                                    <th width="1%" class="text-center">Gambar</th>
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
            processing: true,
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
                { data: 'kelompok.nama', name: 'kelompok.nama' },
                { data: 'nama', name: 'nama' },
                { data: 'qty_item', name: 'qty_item', className: 'text-center' },
                { data: 'satuan', name: 'satuan.nama', className: 'text-center' },
                { data: 'foto_barang', name: 'foto_barang', className: 'text-center' },
                { data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center' }
            ],
            dom: 'Bfrtip',
            buttons: [
                {
                    extend: 'collection',
                    text: 'Export',
                    className: 'btn btn-primary', // Ganti kelas sesuai kebutuhan
                    buttons: [
                        {
                            extend: 'excelHtml5',
                            text: 'Export Excel',
                            title: 'Data Barang',
                            action: function (e, dt, node, config) {
                                exportToExcel(); // Fungsi custom untuk eksport Excel
                            },
                            exportOptions: {
                                columns: ':not(.no-export)' // Eksklusi kolom dengan kelas 'no-export'
                            }
                        },
                        {
                            extend: 'copy',
                            text: 'Copy',
                            exportOptions: {
                                columns: ':not(.no-export)' // Eksklusi kolom dengan kelas 'no-export'
                            }
                        },
                        {
                            extend: 'csv',
                            text: 'CSV',
                            exportOptions: {
                                columns: ':not(.no-export)' // Eksklusi kolom dengan kelas 'no-export'
                            }
                        },
                        {
                            extend: 'pdf',
                            text: 'PDF',
                            exportOptions: {
                                columns: ':not(.no-export)' // Eksklusi kolom dengan kelas 'no-export'
                            }
                        },
                        {
                            extend: 'print',
                            text: 'Print',
                            exportOptions: {
                                columns: ':not(.no-export)' // Eksklusi kolom dengan kelas 'no-export'
                            }
                        }
                    ]
                }
            ],
            initComplete: function() {
                var kelompokSelect = $('<select id="kelompok_filter" class="form-select" style="width: 150px;"><option value="">Semua Kelompok Barang</option></select>')
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
            }
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

        function exportToExcel() {
            var wb = XLSX.utils.book_new();
            var ws_data = [];

            // Tambahkan header tabel
            ws_data.push([
                'NO', 'Uraian Barang', 'Satuan', 'Harga Beli Satuan (Rupiah)', 'Total Persediaan', '', 'Barang Rusak', '', 'Barang Usang', ''
            ]);
            ws_data.push([
                '', 'Satuan', 'Jumlah', 'Harga Total', 'Jumlah', 'Harga Total', 'Jumlah', 'Harga Total', 'Jumlah', 'Harga Total'
            ]);
            ws_data.push([
                '', '', '', '', '', '(Rupiah)', '', '(Rupiah)', '', '(Rupiah)'
            ]);

            // Ambil data dari tabel
            $('#datatable').DataTable().rows().every(function() {
                var data = this.data();
                ws_data.push([
                    '', // NO
                    data.nama, // Uraian Barang
                    data.satuan.nama, // Satuan
                    '', // Harga Beli Satuan (Rupiah)
                    data.qty_item, // Total Persediaan Jumlah
                    '', // Total Persediaan Harga Total
                    '', // Barang Rusak Jumlah
                    '', // Barang Rusak Harga Total
                    '', // Barang Usang Jumlah
                    ''  // Barang Usang Harga Total
                ]);
            });

            // Konversi data ke worksheet
            var ws = XLSX.utils.aoa_to_sheet(ws_data);

            // Mengatur merge dan format
            ws['!merges'] = [
                // Merge cells for the table headers
                { s: {r: 0, c: 0}, e: {r: 0, c: 9} }, // NO
                { s: {r: 1, c: 1}, e: {r: 1, c: 1} }, // Uraian Barang
                { s: {r: 1, c: 2}, e: {r: 1, c: 2} }, // Satuan
                { s: {r: 1, c: 3}, e: {r: 1, c: 3} }, // Harga Beli Satuan (Rupiah)

                // Merge cells for the sub-headers
                { s: {r: 1, c: 4}, e: {r: 1, c: 4} }, // Total Persediaan Jumlah
                { s: {r: 1, c: 5}, e: {r: 1, c: 5} }, // Total Persediaan Harga Total
                { s: {r: 1, c: 6}, e: {r: 1, c: 6} }, // Barang Rusak Jumlah
                { s: {r: 1, c: 7}, e: {r: 1, c: 7} }, // Barang Rusak Harga Total
                { s: {r: 1, c: 8}, e: {r: 1, c: 8} }, // Barang Usang Jumlah
                { s: {r: 1, c: 9}, e: {r: 1, c: 9} }  // Barang Usang Harga Total
            ];

            // Tambahkan worksheet ke workbook
            XLSX.utils.book_append_sheet(wb, ws, 'Data Barang');

            // Simpan workbook ke file Excel
            XLSX.writeFile(wb, 'Data_Barang.xlsx');
        }
    });
</script>


@endsection