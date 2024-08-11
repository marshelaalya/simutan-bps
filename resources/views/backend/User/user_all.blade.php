@extends('admin.admin_master')
@section('admin')

<style>
/* Mengatur elemen pencarian dan dropdown filter */
#datatable_filter {
    display: flex;
    align-items: center;
    gap: 10px;
}

.dataTables_filter {
    margin-left: 10px;
    display: flex;
    align-items: center;
    justify-content: end;
}

#roleFilter {
    width: 20%; /* Atur lebar dropdown sesuai kebutuhan */
}

/* Atur kotak pencarian agar berada di sebelah kanan dropdown filter */
.dataTables_filter input {
    margin-left: 10px; /* Memberi jarak antara dropdown dan kotak pencarian */
}

/* Gaya untuk dropdown tombol export */
.dt-button-collection {
    display: inline-block;
    position: relative;
}

/* .dt-button-collection .dropdown-menu {
    position: absolute;
    top: 100%;
    left: 0;
    z-index: 1000;
    display: block;
    margin: 0;
    padding: 0;
    border: 1px solid #ccc;
    border-radius: 4px;
    background-color: #fff;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
} */

/* .dt-button-collection .dropdown-item {
    display: block;
    padding: 8px 16px;
    color: #333;
    text-decoration: none;
} */

/* .dt-button-collection .dropdown-item:hover {
    background-color: #f8f9fa;
} */
.dt-button-collection .dropdown-toggle {
    display: block !important;
    width: 100% !important;
    padding: .47rem 1.75rem .47rem .75rem !important;
    -moz-padding-start: calc(.75rem - 3px) !important;
    font-size: .9rem !important;
    font-weight: 500 !important;
    line-height: 1.5 !important;
    color: #505d69 !important;
    background-color: #fff !important;
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%230a1832' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M2 5l6 6 6-6'/%3e%3c/svg%3e") !important;
    background-repeat: no-repeat !important;
    background-position: right .75rem center !important;
    background-size: 16px 12px !important;
    border: 1px solid #ced4da !important;
    border-radius: .25rem !important;
    transition: border-color .15s ease-in-out, box-shadow .15s ease-in-out !important;
    appearance: none !important;
}

.dt-button-collection .dropdown-menu {
    border: 1px solid #ced4da !important;
    border-radius: .25rem !important;
    box-shadow: 0 0 0 .15rem rgba(15,156,243,.25) !important;
    background-color: #fff !important;
}

.dt-button-collection .dropdown-item {
    color: #505d69 !important;
    padding: .5rem 1rem !important;
    text-decoration: none !important;
}

.dt-button-collection .dropdown-item:hover {
    background-color: #f8f9fa !important;
}

.dt-button-collection .dropdown-item:focus {
    outline: none !important;
    background-color: #e9ecef !important;
}


</style>

<div class="page-content">
    <div class="container-fluid">
        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0 text-info">List Pengguna</h4>
                
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Pengguna</a></li>
                            <li class="breadcrumb-item active">Seluruh Pengguna</li>
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
                            <h3 class="card-title mb-0">Seluruh Pengguna</h3>
                            <a href="{{ route('user.add') }}" class="btn btn-info waves-effect waves-light ml-3">
                                <i class="mdi mdi-plus-circle"></i> Tambah Pengguna
                            </a>
                        </div>
                                               
                        <table id="datatable" class="table table-bordered yajra-datatable" 
                        style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead>
                                <tr>
                                    <th>Nama Pengguna</th>
                                    <th>Role</th>
                                    <th>Email</th>
                                    <th>Username</th>
                                    <th width="1%" class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Data akan diisi oleh DataTables via AJAX -->
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
  $(function () {
    var table = $('.yajra-datatable').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        scrollX: true,
        ajax: {
            url: "{{ route('user.all') }}",
            data: function (d) {
                d.role = $('#roleFilter').val(); // Mengirimkan data filter ke server
            }
        },
        columns: [
            {data: 'name', name: 'name'},
            {data: 'role', name: 'role'},
            {data: 'email', name: 'email'},
            {data: 'username', name: 'username'},
            {
                data: 'action', 
                name: 'action', 
                orderable: false, 
                searchable: false,
                render: function(data, type, row) {
                    var editUrl = "{{ route('user.edit', ':id') }}".replace(':id', row.id);
                    var deleteUrl = "{{ route('user.delete', ':id') }}".replace(':id', row.id);

                    return `
                    <div class="table-actions" style="text-align: center; vertical-align: middle;">
                        <a href="${editUrl}" class="btn bg-warning btn-sm">
                            <i class="fas fa-edit" style="color: #ca8a04"></i>
                        </a>
                        <a href="${deleteUrl}" class="btn bg-danger btn-sm">
                            <i class="fas fa-trash-alt text-danger"></i>
                        </a>
                    </div>
                    `;
                }
            },
        ],
        dom: 'Brftip',
        buttons: [
            {
                extend: 'collection',
                text: 'Export',
                buttons: [
                    {
                        extend: 'excelHtml5',
                        text: 'Export Excel',
                        title: 'Data Export',
                        exportOptions: {
                            columns: ':visible' // Export all visible columns
                        },
                    },
                    {
                        extend: 'copy',
                        text: 'Copy',
                        exportOptions: {
                            columns: ':visible'
                        },
                    },
                    {
                        extend: 'csv',
                        text: 'CSV',
                        exportOptions: {
                            columns: ':visible'
                        },
                    },
                    {
                        extend: 'pdf',
                        text: 'PDF',
                        exportOptions: {
                            columns: ':visible'
                        },
                    },
                    {
                        extend: 'print',
                        text: 'Print',
                        exportOptions: {
                            columns: ':visible'
                        },
                    }
                ]
            }
        ],
        initComplete: function() {
            var column = this.api().column(1); // Role column

            var select = $('<select id="roleFilter" class="form-select"><option value="">Semua Role</option></select>')
                .appendTo($('#datatable_filter').css('display', 'flex').css('align-items', 'center').css('gap', '10px')) // Tambahkan dropdown ke sebelah search box
                .on('change', function() {
                    table.draw();
                });

            @foreach($roles as $role)
                select.append('<option value="{{ $role->role }}">{{ ucfirst($role->role) }}</option>');
            @endforeach

            $('.dt-button-collection .dropdown-toggle').css({
                'display': 'block',
                'width': '100%',
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
            $('.dt-button-collection .dropdown-menu').css({
                'border': '1px solid #ced4da',
                'border-radius': '.25rem',
                'box-shadow': '0 0 0 .15rem rgba(15,156,243,.25)',
                'background-color': '#fff'
            });
            $('.dt-button-collection .dropdown-item').css({
                'color': '#505d69',
                'padding': '.5rem 1rem',
                'text-decoration': 'none'
            });
            $('.dt-button-collection .dropdown-item:hover').css({
                'background-color': '#f8f9fa'
            });
            $('.dt-button-collection .dropdown-item:focus').css({
                'outline': 'none',
                'background-color': '#e9ecef'
            });
        }
    });

    // Event listener for filtering
    $('#roleFilter').change(function () {
        table.draw();
    });
});

</script>

  

@endsection
