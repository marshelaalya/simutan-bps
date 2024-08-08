@extends(auth()->user()->role === 'admin' ? 'admin.admin_master' : 'pegawai.pegawai_master')

@section(auth()->user()->role === 'admin' ? 'admin' : 'pegawai')

<div class="page-content">
    <div class="container-fluid">
        <!-- Start Page Title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0 text-info">Permintaan {{ $permintaan->no_permintaan }}</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('permintaan.all') }}">Permintaan</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('permintaan.all') }}">View Permintaan</a></li>
                            <li class="breadcrumb-item active">Permintaan {{ $permintaan->no_permintaan }}</li>
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
                        {{-- <div class="row mb-4">
                            <div class="col-sm-6">
                                <p><strong>Nomor Permintaan     :</strong> {{ $permintaan->no_permintaan }}</p>
                                <p><strong>Nama Pegawai         :</strong> {{ $permintaan->user->name }}</p>
                                <p><strong>Tanggal Permintaan   :</strong> {{ $permintaan->tgl_request }}</p>
                                <p><strong>Status               :</strong> {{ $permintaan->status }}</p>
                                <p><strong>Deskripsi            :</strong> {{ $permintaan->pilihan->first()->description }}</p>
                                <p><strong>Catatan Admin        :</strong> {{ $permintaan->ctt_adm }}</p>
                                <p><strong>Catatan Supervisor   :</strong> {{ $permintaan->ctt_spv }}</p>
                            </div>
                        </div> --}}

                        <table class="table table-centered mb-0 align-middle" style="border: 1px solid white;">
                            <tr>
                                <td style="white-space: nowrap; width: 1%; min-width: 200px;"><strong>Nomor Permintaan</strong></td>
                                <td>{{ $permintaan->no_permintaan }}</td>
                            </tr>
                            <tr>
                                <td style="white-space: nowrap; width: 1%; min-width: 200px;"><strong>Nama Pegawai</strong></td>
                                <td>{{ $permintaan->user->name }}</td>
                            </tr>
                            <tr>
                                <td style="white-space: nowrap; width: 1%; min-width: 200px;"><strong>Tanggal Permintaan</strong></td>
                                <td>{{ $permintaan->tgl_request }}</td>
                            </tr>
                            <tr>
                                <td style="white-space: nowrap; width: 1%; min-width: 200px;"><strong>Status</strong></td>
                                <td>{{ $permintaan->status }}</td>
                            </tr>
                            <tr>
                                <td style="white-space: nowrap; width: 1%; min-width: 200px;"><strong>Deskripsi</strong></td>
                                <td>{{ $permintaan->pilihan->first()->description }}</td>
                            </tr>
                            <tr>
                                <td style="white-space: nowrap; width: 1%; min-width: 200px;"><strong>Catatan Admin</strong></td>
                                <td>{{ $permintaan->ctt_adm ?: '-' }}</td>
                            </tr>
                            <tr>
                                <td style="white-space: nowrap; width: 1%; min-width: 200px;"><strong>Catatan Supervisor</strong></td>
                                <td>{{ $permintaan->ctt_spv ?: '-' }}</td>
                            </tr>
                        </table>
                        <br>

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
                                            <td style="white-space: nowrap; width: 1%; min-width: 400px;">{{ $item->req_qty }} {{ $item->barang->satuan }}</td>
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
                        <div class="mt-4 d-flex justify-content-end">
                            
                            <a href="{{ route('permintaan.all') }}" class="btn btn-secondary">Kembali</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
