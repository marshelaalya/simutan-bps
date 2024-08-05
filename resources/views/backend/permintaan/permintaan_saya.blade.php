@extends('admin.admin_master')
@section('admin')


 <div class="page-content">
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Permintaan All</h4>

                                     

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
                                <th>Tanggal</th> {{-- Tanggal Permintaan --}}
                                <th>Nama Pengaju</th>
                                <th>Catatan</th>
                                <th width = 15%>Approval Admin</th>
                                <th width = 15%>Approval Supervisor</th>
                                <th>Action</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($permintaans as $key => $item)
                                <tr>
                                    <td>
                                        @foreach($item->pilihan as $pilihan)
                                            {{ $pilihan->date }}<br>
                                        @endforeach
                                    </td>
                                    <td>
                                        @foreach($item->pilihan as $pilihan)
                                            {{ $pilihan->created_by }}<br>
                                        @endforeach
                                    </td>
                                    <td>
                                        @foreach($item->pilihan as $pilihan)
                                            {{ $pilihan->description }}<br>
                                        @endforeach
                                    </td>
                                    <td style="text-align: center; vertical-align: middle;">
                                        @if($item->status == 'pending')
                                            <button class="btn btn-secondary bg-warning btn-sm d-flex align-items-center justify-content-center font-size-13" 
                                                    style="border: 0; color: #ca8a04; pointer-events: none; cursor: not-allowed;">
                                                Pending
                                            </button>
                                        @elseif($item->status == 'rejected by admin')
                                            <button class="btn btn-secondary bg-danger btn-sm d-flex align-items-center justify-content-center font-size-13" 
                                                    style="border: 0; color: #fff; pointer-events: none; cursor: not-allowed;">
                                                Rejected
                                            </button>
                                        @elseif($item->status == 'accepted by admin')
                                            <button class="btn btn-secondary bg-success btn-sm d-flex align-items-center justify-content-center font-size-13" 
                                                    style="border: 0; color: #fff; pointer-events: none; cursor: not-allowed;">
                                                Accepted
                                            </button>
                                        @endif
                                    </td>
                                    <td style="text-align: center; vertical-align: middle;">
                                        @if($item->status == 'pending')
                                            <button class="btn btn-secondary bg-warning btn-sm d-flex align-items-center justify-content-center font-size-13" 
                                                    style="border: 0; color: #ca8a04; pointer-events: none; cursor: not-allowed;">
                                                Pending
                                            </button>
                                        @elseif($item->status == 'rejected by supervisor')
                                            <button class="btn btn-secondary bg-danger btn-sm d-flex align-items-center justify-content-center font-size-13" 
                                                    style="border: 0; color: #fff; pointer-events: none; cursor: not-allowed;">
                                                Rejected
                                            </button>
                                        @elseif($item->status == 'accepted by supervisor')
                                            <button class="btn btn-secondary bg-success btn-sm d-flex align-items-center justify-content-center font-size-13" 
                                                    style="border: 0; color: #fff; pointer-events: none; cursor: not-allowed;">
                                                Accepted
                                            </button>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('barang.edit', $item->id) }}" class="btn bg-warning btn-sm">
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