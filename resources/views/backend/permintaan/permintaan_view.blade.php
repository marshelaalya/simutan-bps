<!-- resources/views/permintaan/approve.blade.php -->

@extends('admin.admin_master')
@section('admin')

<div class="page-content">
    <div class="container-fluid">
        <!-- Start Page Title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0 text-info">Approval Permintaan</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('permintaan.all') }}">Permintaan</a></li>
                            <li class="breadcrumb-item active">Approve Permintaan</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Page Title -->

        <!-- Approval Information -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Informasi Permintaan</h5>
                        <hr>

                        <!-- Display Permintaan Details -->
                        <div class="row mb-4">
                            <div class="col-sm-6">
                                <p><strong>Nomor Permintaan     :</strong> {{ $permintaan->no_permintaan }}</p>
                                <p><strong>Nama Pengaju         :</strong> {{ $permintaan->user->name }}</p>
                                <p><strong>Tanggal Permintaan   :</strong> {{ $permintaan->tgl_request }}</p>
                                <p><strong>Status               :</strong> {{ $permintaan->status }}</p>
                                <p><strong>Deskripsi            :</strong> {{ $permintaan->pilihan->first()->description }}</p>
                                <p><strong>Catatan Admin        :</strong> {{ $permintaan->ctt_adm }}</p>
                                <p><strong>Catatan Supervisor   :</strong> {{ $permintaan->ctt_spv }}</p>
                            </div>
                        </div>

                        <!-- Display Pilihan Details -->
                        <h5 class="card-title">Detail Barang Permintaan</h5>
                        <hr>
                        <div class="table-responsive">
                            <table class="table table-centered mb-0 align-middle table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Nama Barang</th>
                                        <th>Kuantitas</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($pilihan as $item)
                                        <tr>
                                            <td>{{ $item->barang->nama }}</td>
                                            <td>{{ $item->req_qty }} {{ $item->barang->satuan }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="text-center">Tidak ada barang terpilih</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- Approval Actions -->
                        <div class="mt-4">
                            
                            <a href="{{ route('permintaan.all') }}" class="btn btn-secondary">Kembali</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
