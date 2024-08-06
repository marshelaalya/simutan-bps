@extends('admin.admin_master')
@section('admin')


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

                    <table id="datatable" class="table table-bordered dt-responsive nowrap" 
                        style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead>
                            <tr>
                                <th width = 1%>Tanggal</th> {{-- Tanggal Permintaan --}}
                                <th width = 1%>Nama Pengaju</th>
                                <th>Catatan</th>
                                <th width = 1% class="text-center">Approval Admin</th>
                                <th width = 1% class="text-center">Approval Supervisor</th>
                                <th width = 1% class="text-center">Aksi</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($permintaans as $key => $item)
                                <tr>
                                    <td>
                                        {{ $item->pilihan->first()->date ?? 'Tidak ada data' }}
                                    </td>
                                    <td>
                                        {{ $item->pilihan->first()->created_by ?? 'Tidak ada data' }}
                                    </td>
                                    <td>
                                        {{ $item->pilihan->first()->description ?? 'Tidak ada data' }}
                                    </td>
                                    <td class="text-center align-middle justify-content-center">
                                        @if($item->status == 'pending')
                                            <button class="btn btn-secondary bg-warning btn-sm font-size-13" 
                                                    style="border: 0; color: #ca8a04; pointer-events: none; cursor: not-allowed;">
                                                Pending
                                            </button>
                                        @elseif($item->status == 'rejected by admin')
                                            <button class="btn btn-secondary bg-danger btn-sm font-size-13" 
                                                    style="border: 0; color: #fff; pointer-events: none; cursor: not-allowed;">
                                                Rejected
                                            </button>
                                        @elseif($item->status == 'approved by admin')
                                            <button class="btn btn-secondary bg-success btn-sm font-size-13" 
                                                    style="border: 0; color: #fff; pointer-events: none; cursor: not-allowed;">
                                                Approved
                                            </button>
                                        @endif
                                    </td>
                                    <td class="text-center align-middle justify-content-center">
                                        @if($item->status == 'approved by admin' || $item->status == 'pending')
                                            <button class="btn btn-secondary bg-warning btn-sm font-size-13" 
                                                    style="border: 0; color: #ca8a04; pointer-events: none; cursor: not-allowed;">
                                                Pending
                                            </button>
                                        @elseif($item->status == 'rejected by supervisor')
                                            <button class="btn btn-secondary bg-danger btn-sm font-size-13" 
                                                    style="border: 0; color: #fff; pointer-events: none; cursor: not-allowed;">
                                                Rejected
                                            </button>
                                        @elseif($item->status == 'approved by supervisor')
                                            <button class="btn btn-secondary bg-success btn-sm font-size-13" 
                                                    style="border: 0; color: #fff; pointer-events: none; cursor: not-allowed;">
                                                Approved
                                            </button>
                                        @endif
                                    </td>
                                    
                                    <td class="text-center">
                                        <a href="{{ route('barang.edit', $item->id) }}" class="btn bg-warning btn-sm">
                                            <i class="fas fa-edit" style="color: #ca8a04"></i>
                                        </a>
                                        <a href="{{ route('permintaan.approve', $item->id) }}" class="btn bg-warning btn-sm">
                                            <i class="fas fa-edit" style="color: #ca8a04"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                </div> <!-- end col -->
            </div> <!-- end row -->              
        </div> <!-- container-fluid -->
    </div>
@endsection