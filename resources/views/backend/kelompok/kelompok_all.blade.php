@extends('admin.admin_master')
@section('admin')

<div class="page-content">
    <div class="container-fluid">
        <!-- start page title -->
<div class="row">
    <div class="col-12">
    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
        <h4 class="mb-sm-0 text-info">List Kelompok Barang</h4>
    
        <div class="page-title-right">
            <ol class="breadcrumb m-0">
                <li class="breadcrumb-item"><a href="javascript: void(0);">Barang</a></li>
                <li class="breadcrumb-item active">List Kelompok Barang</li>
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
                            <h4 class="card-title mb-0">Kelompok Barang</h4>
                            <a href="{{ route('kelompok.add') }}" class="btn btn-info waves-effect waves-light ml-3">
                                <i class="mdi mdi-plus-circle"></i> Tambah Kelompok Barang
                            </a>
                        </div>
                                               
                        <table id="datatable" class="table table-bordered dt-responsive nowrap" 
                        style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            
                        <thead>
                            <tr>
                                <th width="1%">ID</th>
                                <th>Nama</th>
                                <th width="1%">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($kelompoks as $item)
                                <tr>
                                    <td>{{ $item->id }}</td>
                                    <td>{{ $item->nama }}</td>
                                    <td class="table-actions" style="text-align: center; vertical-align: middle;">
                                        <!-- Tombol dengan link route ke halaman view -->
                                        <a href="{{ route('kelompok.edit', $item->id) }}" class="btn bg-warning btn-sm">
                                            <i class="fas fa-edit" style="color: #ca8a04"></i>
                                        </a>
                                        
                                        <!-- Tombol dengan link route ke halaman print -->
                                        <a href="{{ route('kelompok.delete', $item->id) }}" class="btn bg-danger btn-sm">
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
