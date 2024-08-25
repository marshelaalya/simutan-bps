@extends(auth()->user()->role === 'admin' ? 'admin.admin_master' : 
         (auth()->user()->role === 'supervisor' ? 'supervisor.supervisor_master' : 
         'pegawai.pegawai_master'))

@section(auth()->user()->role === 'admin' ? 'admin' : 
         (auth()->user()->role === 'supervisor' ? 'supervisor' : 'pegawai'))


<style>
    .filter-buttons {
        display: flex;
        align-items: center;
    }
    
    .filter-buttons .form-select-sm {
        min-width: 180px;
        margin-right: 10px; /* Adjust as needed */
    }
    
    .datatable-search {
        flex-grow: 1; /* Ensure it takes the remaining space */
    }
    
    a[data-tooltip] {
        position: relative;
    }

    #datatable_filter {
    justify-content: end;
}
    
    a[data-tooltip]::before {
        content: attr(data-tooltip);
        position: absolute;
        bottom: 100%; /* Tooltip berada di atas elemen */
        left: 50%;
        transform: translateX(-50%);
        background-color: #333;
        color: #fff;
        padding: 5px 10px;
        border-radius: 5px;
        white-space: nowrap;
        opacity: 0;
        transition: opacity 0.1s ease-in-out; /* Percepat transisi menjadi 0.1s */
        pointer-events: none;
        font-size: 12px;
        z-index: 999;
    }
    
    a[data-tooltip]:hover::before {
        opacity: 1;
    }
    
    .hover\:bg-primary:hover {
        background-color: #e3f0fb!important; /* Sesuaikan dengan warna bg-primary */
        color: #007bff !important;
    }
    
    .hover\:bg-success:hover {
        background-color: #e2f6e7 !important; /* Sesuaikan dengan warna bg-success */
        color: #28a745 !important;
    }
    
    .hover\:bg-danger:hover {
        background-color: #feeaea !important; /* Sesuaikan dengan warna bg-danger */
        color: #dc3545 !important;
    }

    .hover\:bg-warning:hover {
        --bs-bg-opacity:0.15;
        background-color:rgba(var(--bs-warning-rgb),var(--bs-bg-opacity))!important
    }
    
    
    </style>

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

                        <table id="datatable" class="table table-bordered yajra-datatable" 
                            style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead>
                                <tr>
                                    <th class="align-content-center">Tanggal</th>
                                    <th class="align-content-center">Nama Pegawai</th>
                                    <th class="align-content-center">Catatan</th>
                                    <th width="17%" class="align-content-center" style="white-space: nowrap;">Status</th>
                                   
                                    <th width="1%" class="text-center align-content-center">Aksi</th>
                                </tr>
                            </thead>

                            <tbody>
                                <!-- DataTable akan mengisi baris di sini -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript libraries for DataTables -->
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
                url: "{{ route('permintaan.saya') }}",
                data: function(d) {
                    d.admin_approval = $('#admin_approval_filter').val();
                    d.supervisor_approval = $('#supervisor_approval_filter').val();
                }
            },
            columns: [
                { data: 'date', name: 'date', className: 'align-content-center' },
                { data: 'created_by', name: 'created_by', className: 'align-content-center' },
                { data: 'description', name: 'description', className: 'align-content-center' },
                {
                    data: 'approval_status',
                    name: 'approval_status',
                    searchable: false,
                    className: 'text-center align-content-center'
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false,
                    className: 'text-center align-content-center no-export',
                    render: function(data, type, row) {
                        var viewUrl = "{{ route('permintaan.view', ':id') }}".replace(':id', row.id);
var editUrl = "{{ route('permintaan.edit', ':id') }}".replace(':id', row.id);
var deleteUrl = "{{ route('permintaan.delete', ':id') }}".replace(':id', row.id);

var viewButton = `
    <a href="${viewUrl}" class="btn btn-sm text-primary hover:bg-primary" style="width: 20px; height: 20px; display: flex; align-items: center; justify-content: center; text-decoration: none; color: blue; padding: 15px;" data-tooltip="Lihat Permintaan">
        <i class="ti ti-eye font-size-20 align-middle"></i>
    </a>
`;

var deleteButton = row.status === 'pending' ? `
    <a href="${deleteUrl}" class="btn btn-sm text-danger hover:bg-danger" style="width: 20px; height: 20px; display: flex; align-items: center; justify-content: center; text-decoration: none; color: red; padding: 15px;" data-tooltip="Hapus Permintaan">
        <i class="ti ti-trash font-size-20 align-middle text-danger"></i>
    </a>
` : `
    <a href="${deleteUrl}" class="btn btn-sm text-secondary" style="width: 20px; height: 20px; display: flex; align-items: center; justify-content: center; text-decoration: none; color: gray; pointer-events: none; opacity: 0.5; padding: 15px;" data-tooltip="Hapus Permintaan">
        <i class="ti ti-trash font-size-20 align-middle"></i>
    </a>
`;

var editButton = row.status === 'pending' ? `
    <a href="${editUrl}" class="btn btn-sm hover:bg-warning" style="width: 20px; height: 20px; display: flex; align-items: center; justify-content: center; text-decoration: none; color: #e1a017; padding: 15px;" data-tooltip="Edit Permintaan">
        <i class="ti ti-edit font-size-20 align-middle"></i>
    </a>
` : `
    <a href="${editUrl}" class="btn btn-sm text-secondary" style="width: 20px; height: 20px; display: flex; align-items: center; justify-content: center; text-decoration: none; color: gray; pointer-events: none; opacity: 0.5; padding: 15px;" data-tooltip="Edit Permintaan">
        <i class="ti ti-edit font-size-20 align-middle"></i>
    </a>
`;

return `
    <div class="text-center d-flex justify-content-center align-items-center">
        ${viewButton}
        ${editButton}
        ${deleteButton}
    </div>
`;

                    }
                }
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
                            title: 'Data Export',
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
                // Filter untuk admin approval
                // Filter untuk approval admin
var adminSelect = $('<select id="admin_approval_filter" class="form-select" style="width: 20%;"><option value="">Semua Status Admin</option></select>')
.appendTo($('#datatable_filter').css('display', 'flex').css('align-items', 'center').css('gap', '10px')).css('justify-content', 'end')
.on('change', function() {
table.draw();
});

// Menambahkan opsi untuk approval admin
adminSelect.append('<option value="pending">Admin Pending</option>');
adminSelect.append('<option value="approved by admin">Admin Approved</option>');
adminSelect.append('<option value="rejected by admin">Admin Rejected</option>');

// Filter untuk approval supervisor
var supervisorSelect = $('<select id="supervisor_approval_filter" class="form-select" style="width: 23%;"><option value="">Semua Status Supervisor</option></select>')
.appendTo($('#datatable_filter').css('display', 'flex').css('align-items', 'center').css('gap', '10px'))
.on('change', function() {
table.draw();
});

// Menambahkan opsi untuk approval supervisor
supervisorSelect.append('<option value="pending">Supervisor Pending</option>');
supervisorSelect.append('<option value="approved by supervisor">Supervisor Approved</option>');
supervisorSelect.append('<option value="rejected by supervisor">Supervisor Rejected</option>');

    
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
    });
    </script>
@endsection