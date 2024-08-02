@extends('admin.admin_master')
@section('admin')
<!-- jQuery -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<!-- DataTables CSS -->
<link rel="stylesheet" href="assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="assets/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css">

<!-- DataTables JS -->
<script src="assets/libs/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js"></script>

<!-- DataTables Buttons JS -->
<script src="assets/libs/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
<script src="assets/libs/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js"></script>
<script src="assets/libs/jszip/jszip.min.js"></script>
<script src="assets/libs/pdfmake/build/pdfmake.min.js"></script>
<script src="assets/libs/pdfmake/build/vfs_fonts.js"></script>
<script src="assets/libs/datatables.net-buttons/js/buttons.html5.min.js"></script>
<script src="assets/libs/datatables.net-buttons/js/buttons.print.min.js"></script>
<script src="assets/libs/datatables.net-buttons/js/buttons.colVis.min.js"></script>

<!-- DataTables KeyTable and Select -->
<script src="assets/libs/datatables.net-keytable/js/dataTables.keyTable.min.js"></script>
<script src="assets/libs/datatables.net-select/js/dataTables.select.min.js"></script>


<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Halaman Barang Ajuan</h4>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">

                    <div class="card-body">

                        <a href="{{ route('pilihan.add') }}" class="btn btn-dark btn-rounded waves-effect waves-light" 
                        style="float:right;">Ajukan Permintaan</a> <br>

                        <h4 class="card-title">Halaman Pengajuan Barang</h4>

                        <table id="datatable-buttons" class="table table-striped table-bordered dt-responsive nowrap" 
                        style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Pengaju</th>
                                    <th>Pilihan</th>
                                    <th>ID Permintaan</th>
                                    <th>Barang</th>
                                    <th>Deskripsi</th>
                                    <th>Jumlah Permintaan</th>
                                    <th width="20%">Action</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach($pilihans as $key => $item)
                                <tr>
                                    <td>{{ $item->id }}</td>
                                    <td>{{ $item->pilihan_no }}</td>
                                    <td>{{ $item->permintaan_id }}</td>
                                    <td>{{ $item->barang->nama ?? 'N/A' }}</td>
                                    <td>{{ $item->description }}</td>
                                    <td>{{ $item->req_qty }}</td>
                                    <td>{{ $item->satuan }}</td>
                                    <td>
                                        <a href="{{ route('barang.edit', $item->id) }}" class="btn btn-info sm" title="Edit Data"><i class="fas fa-edit"></i></a>
                                        <a href="{{ route('barang.delete', $item->id) }}" class="btn btn-danger sm" title="Delete Data" id="delete"><i class="fas fa-trash-alt"></i></a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>                         
                            

                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        $('#example').DataTable({
            dom: 'Bfrtip', // Menampilkan button
            buttons: [
                'copy', 'excel', 'pdf', 'print', 'colvis'
            ]
        });
    });
</script>


@endsection
