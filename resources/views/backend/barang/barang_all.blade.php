@extends('admin.admin_master')
@section('admin')

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
                                               
                        <table id="datatable" class="table table-bordered dt-responsive nowrap" 
                        style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            
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
                                @foreach($barangs as $item)
                                    <tr>
                                        <td width="1%" class="text-center">{{ $item->kode }}</td>
                                        <td width="20%">{{ $item->kelompok->nama ?? 'N/A' }}</td>
                                        <td>{{ $item->nama }}</td>
                                        <td width="1%" class="text-center">{{ $item->qty_item }}</td>
                                        <td width="1%" class="text-center">{{ $item->satuan }}</td>
                                        
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
