@extends('admin.admin_master')
@section('admin')

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
                        <table id="datatable" class="table table-bordered dt-responsive nowrap" 
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

@endsection
