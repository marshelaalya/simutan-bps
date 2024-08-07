@extends('admin.admin_master')
@section('admin')
<!-- jQuery -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<div class="page-content">
    <div class="container-fluid">

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
                                               
                        <table id="datatable" class="table table-bordered dt-responsive nowrap" 
                        style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Pegawai</th>
                                <th>Pilihan</th>
                                <th>ID Permintaan</th>
                                <th>Barang</th>
                                <th>Deskripsi</th>
                                <th>Jumlah Permintaan</th>
                                <th width="20%" class="text-center">Aksi</th>
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
                                <td class="table-actions" style="text-align: center; vertical-align: middle;">
                                    <!-- Tombol dengan link route ke halaman view -->
                                    <a href="{{ route('barang.edit', $item->id) }}" class="btn bg-warning btn-sm">
                                        <i class="fas fa-edit" style="color: #ca8a04"></i>
                                    </a>
                                    
                                    <!-- Tombol dengan link route ke halaman print -->
                                    <a href="{{ route('barang.delete', $item->id) }}" class="btn bg-danger btn-sm">
                                        <i class="fas fa-trash-alt text-danger"></i>
                                    </a>
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

<script>
    $(document).ready(function() {
    $('#datatable').DataTable({
        initComplete: function() {
            $('#datatable thead').css('background-color', '#043277').css('color', 'white');
        }
    });
});

</script>

@endsection
