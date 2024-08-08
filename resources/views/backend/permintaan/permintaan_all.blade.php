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
                                <th width=1%>Tanggal</th> {{-- Tanggal Permintaan --}}
                                <th width=1%>Nama Pegawai</th>
                                <th style="word-wrap: break-word; word-break: break-all; white-space: normal;">Catatan</th>
                                <th width=1% class="text-center">Approval Admin</th>
                                <th width=1% class="text-center">Approval Supervisor</th>
                                <th width=1% class="text-center">Aksi</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($permintaans as $key => $item)
                                <tr>
                                    <td style="white-space: nowrap;">
                                        {{ $item->pilihan->first()->date ?? 'Tidak ada data' }}
                                    </td>
                                    <td style="white-space: nowrap;">
                                        {{ $item->pilihan->first()->created_by ?? 'Tidak ada data' }}
                                    </td>
                                    <td style="word-wrap: break-word; word-break: break-all; white-space: normal;">
                                        {{ $item->pilihan->first()->description ?? 'Tidak ada data' }}
                                    </td>
                                    <td class="text-center align-middle justify-content-center" style="white-space: nowrap;">
                                        @if($item->status == 'pending')
                                            <button class="btn btn-secondary bg-warning btn-sm font-size-13" 
                                                    style="border: 0; color: #ca8a04; pointer-events: none; cursor: not-allowed;">
                                                Pending
                                            </button>
                                        @elseif($item->status == 'rejected by admin')
                                            <button class="btn btn-secondary bg-danger text-danger btn-sm font-size-13" 
                                                    style="border: 0; pointer-events: none; cursor: not-allowed;">
                                                Rejected
                                            </button>
                                        @elseif($item->status == 'approved by admin' || $item->status == 'rejected by supervisor')
                                            <button class="btn btn-secondary bg-success text-success btn-sm font-size-13" 
                                                    style="border: 0; pointer-events: none; cursor: not-allowed;">
                                                Approved
                                            </button>
                                        @endif
                                    </td>
                                    <td class="text-center align-middle justify-content-center" style="white-space: nowrap;">
                                        @if($item->status == 'approved by admin' || $item->status == 'pending')
                                            <button class="btn btn-secondary bg-warning btn-sm font-size-13" 
                                                    style="border: 0; color: #ca8a04; pointer-events: none; cursor: not-allowed;">
                                                Pending
                                            </button>
                                        @elseif($item->status == 'rejected by supervisor' || $item->status == 'rejected by admin')
                                            <button class="btn btn-secondary bg-danger text-danger btn-sm font-size-13" 
                                                    style="border: 0; pointer-events: none; cursor: not-allowed;">
                                                Rejected
                                            </button>
                                        @elseif($item->status == 'approved by supervisor')
                                            <button class="btn btn-secondary bg-success text-success btn-sm font-size-13" 
                                                    style="border: 0; pointer-events: none; cursor: not-allowed;">
                                                Approved
                                            </button>
                                        @endif
                                    </td>
                                                                     
                                    <td class="text-center d-flex justify-content-center align-items-center"> 
                                        @if($item->status == 'pending')
                                            <a href="{{ route('permintaan.view', $item->id) }}" class="btn bg-primary btn-sm me-2" style="width: 30px; height: 30px; padding: 0; display: flex; align-items: center; justify-content: center; text-decoration: none;">
                                                <i class="ri-eye-fill align-middle text-primary"></i>
                                            </a>
                                            <a href="{{ route('permintaan.approve', $item->id) }}" class="btn bg-success btn-sm" style="width: 30px; height: 30px; padding: 0; display: flex; align-items: center; justify-content: center; text-decoration: none;">
                                                <i class="fas fa-clipboard-check text-success align-middle"></i>
                                            </a>
                                        @elseif($item->status == 'approved by admin' || $item->status == 'rejected by supervisor' || $item->status == 'rejected by admin')
                                            <a href="{{ route('permintaan.view', $item->id) }}" class="btn bg-primary btn-sm me-2" style="width: 30px; height: 30px; padding: 0; display: flex; align-items: center; justify-content: center; text-decoration: none;">
                                                <i class="ri-eye-fill align-middle text-primary"></i>
                                            </a>
                                        @elseif($item->status == 'finished')
                                            <a href="{{ route('permintaan.view', $item->id) }}" class="btn bg-primary btn-sm me-2" style="width: 30px; height: 30px; padding: 0; display: flex; align-items: center; justify-content: center; text-decoration: none;">
                                                <i class="ri-eye-fill align-middle text-primary"></i>
                                            </a>
                                            <a href="{{ route('permintaan.all', $item->id) }}" class="btn bg-secondary btn-sm" style="width: 30px; height: 30px; padding: 0; display: flex; align-items: center; justify-content: center; text-decoration: none;">
                                                <i class="fas fa-print text-white align-middle"></i>
                                            </a>
                                        {{-- @elseif($item->status == 'rejected by admin')
                                            <a href="{{ route('permintaan.view', $item->id) }}" class="btn bg-primary btn-sm" style="width: 30px; height: 30px; padding: 0; display: flex; align-items: center; justify-content: center; text-decoration: none;">
                                                <i class="ri-eye-fill align-middle text-primary"></i>
                                            </a> --}}
                                        @endif
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