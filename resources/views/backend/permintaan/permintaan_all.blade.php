@extends(auth()->user()->role === 'admin' ? 'admin.admin_master' : 'supervisor.supervisor_master')
@section(auth()->user()->role === 'admin' ? 'admin' : 'supervisor')

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


</style>

<div class="page-content">
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0 text-info">List Semua Permintaan</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Permintaan</a></li>
                            <li class="breadcrumb-item active">Semua Permintaan</li>
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
                            <h4 class="card-title mb-0">Permintaan Barang</h4>
                            <a href="{{ route('pilihan.add') }}" class="btn btn-info waves-effect waves-light ml-3">
                                <i class="mdi mdi-plus-circle"></i> Ajukan Permintaan
                            </a>
                        </div>

                        <!-- HTML for Filters -->
<!-- user_all.blade.php -->

<div class="d-flex justify-content-between align-items-center">
    <div class="filter-buttons d-flex me-3">
        <select id="admin_approval_filter" class="form-select form-select-sm me-2">
            <option value="">Filter Admin Approval</option>
            <option value="approved by admin">Approved</option>
            <option value="rejected by admin">Rejected</option>
            <option value="pending">Pending</option>
        </select>

        <select id="supervisor_approval_filter" class="form-select form-select-sm">
            <option value="">Filter Supervisor Approval</option>
            <option value="approved by supervisor">Approved</option>
            <option value="rejected by supervisor">Rejected</option>
            <option value="pending">Pending</option>
        </select>
    </div>
    <div class="datatable-search"></div>
</div>




                        <table id="datatable" class="table table-bordered yajra-datatable" 
                            style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Nama Pegawai</th>
                                    <th>Catatan</th>
                                    <th>Approval Admin</th>
                                    <th>Approval Supervisor</th>
                                    <th width="1%" class="text-center">Aksi</th>
                                </tr>
                            </thead>

                            <tbody>
                                <!-- DataTable akan mengisi baris di sini -->
                            </tbody>
                        </table>

                    </div> <!-- end col -->
                </div> <!-- end row -->              
            </div> <!-- container-fluid -->
        </div>

        <!-- JavaScript libraries for DataTables -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.flash.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>

        <script>
            // permintaan_all.blade.php

            $(document).ready(function() {
    var table = $('#datatable').DataTable({
        dom: '<"d-flex justify-content-between align-items-center"<"filter-buttons d-flex me-3"><"datatable-search"f>>rtip',
        processing: true,
        serverSide: true,
        ajax: {
            url: "{{ route('permintaan.data') }}",
            data: function(d) {
                d.admin_approval = $('#admin_approval_filter').val();
                d.supervisor_approval = $('#supervisor_approval_filter').val();
            }
        },
        columns: [
            { data: 'date', name: 'date' },
            { data: 'created_by', name: 'created_by' },
            { data: 'description', name: 'description' },
            { data: 'admin_approval', name: 'admin_approval', orderable: false, searchable: false, className: "text-center" },
            { data: 'supervisor_approval', name: 'supervisor_approval', orderable: false, searchable: false, className: "text-center" },
            { data: 'action', name: 'action', orderable: false, searchable: false, className: "text-center" }
        ]
    });

    $('#admin_approval_filter, #supervisor_approval_filter').change(function() {
        table.draw();
    });
});



        </script>
        
@endsection
