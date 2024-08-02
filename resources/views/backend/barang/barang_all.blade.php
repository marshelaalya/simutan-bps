@extends('admin.admin_master')
@section('admin')

<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">List Persediaan Barang</h4>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">

                    <div class="card-body">
                        
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <h4 class="card-title mb-0">Persediaan Barang</h4>
                            <a href="{{ route('barang.add') }}" class="btn btn-info waves-effect waves-light ml-3">
                                <i class="mdi mdi-plus-circle"></i> Tambah Barang
                            </a>
                        </div>
                                               
                        <table id="datatable" class="table table-bordered dt-responsive nowrap" 
                        style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            
                            <thead>
                                <tr>
                                    <th width="1%" class="text-center">Kode</th>
                                    <th>Nama Barang</th>
                                    <th width="20%">Kelompok Barang</th>
                                    <th width="1%" class="text-center">Stok</th>
                                    <th width="1%" class="text-center">Satuan</th>
                                    <th width="1%" class="text-center">Aksi</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach($barangs as $item)
                                    <tr>
                                        <td class="text-center">{{ $item->id }}</td>
                                        <td>{{ $item->nama }}</td>
                                        <td>{{ $item->kelompok->nama ?? 'N/A' }}</td>
                                        <td class="text-center">{{ $item->qty_item }}</td>
                                        <td class="text-center">{{ $item->satuan }}</td>
                                        
                                            {{-- <a href="{{ route('barang.edit', $item->id) }}" class="btn btn-info sm" title="Edit Data"> <i class="fas fa-edit"></i> </a>
                                            <a href="{{ route('barang.delete', $item->id) }}" class="btn btn-danger sm" title="Delete Data" id="delete"> <i class="fas fa-trash-alt"></i> </a> --}}
                                        
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

@endsection
