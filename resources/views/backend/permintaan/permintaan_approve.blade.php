@extends(auth()->user()->role === 'admin' ? 'admin.admin_master' : 'supervisor.supervisor_master')
@section(auth()->user()->role === 'admin' ? 'admin' : 'supervisor')


<link href="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">



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
                                <td style="white-space: nowrap; width: 1%; min-width: 200px;"><strong>Approval Admin</strong></td>
                                <td style="white-space: nowrap;">
                                    @if($permintaan->status == 'pending')
                                        <button class="btn btn-secondary bg-warning btn-sm font-size-13" 
                                                style="border: 0; color: #ca8a04; pointer-events: none; cursor: not-allowed;">
                                            Pending
                                        </button>
                                    @elseif($permintaan->status == 'rejected by admin')
                                        <button class="btn btn-secondary bg-danger text-danger btn-sm font-size-13" 
                                                style="border: 0; pointer-events: none; cursor: not-allowed;">
                                            Rejected
                                        </button>
                                    @elseif($permintaan->status == 'approved by admin' || $permintaan->status == 'rejected by supervisor')
                                        <button class="btn btn-secondary bg-success text-success btn-sm font-size-13" 
                                                style="border: 0; pointer-events: none; cursor: not-allowed;">
                                            Approved
                                        </button>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td style="white-space: nowrap; width: 1%; min-width: 200px;"><strong>Approval Supervisor</strong></td>
                                <td style="white-space: nowrap;">
                                        @if($permintaan->status == 'approved by admin' || $permintaan->status == 'pending')
                                            <button class="btn btn-secondary bg-warning btn-sm font-size-13" 
                                                    style="border: 0; color: #ca8a04; pointer-events: none; cursor: not-allowed;">
                                                Pending
                                            </button>
                                        @elseif($permintaan->status == 'rejected by supervisor' || $permintaan->status == 'rejected by admin')
                                            <button class="btn btn-secondary bg-danger text-danger btn-sm font-size-13" 
                                                    style="border: 0; pointer-events: none; cursor: not-allowed;">
                                                Rejected
                                            </button>
                                        @elseif($permintaan->status == 'approved by supervisor')
                                            <button class="btn btn-secondary bg-success text-success btn-sm font-size-13" 
                                                    style="border: 0; pointer-events: none; cursor: not-allowed;">
                                                Approved
                                            </button>
                                        @endif
                                    </td>
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
                                        <th class="text-center">Kuantitas</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($pilihan as $item)
                                        <tr>
                                            <td>{{ $item->barang->nama }}</td>
                                            <td class="text-center" style="white-space: nowrap; width: 1%; min-width: 400px;">
                                                {{ $item->req_qty }} {{ $item->satuan->nama ?? 'N/A' }}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="2" class="text-center">Tidak ada barang terpilih</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- Approval Actions -->
                        <div class="mt-4 d-flex justify-content-end">
                            <!-- Form Approval -->
                            <form action="{{ route('permintaan.updateStatus', $permintaan->id) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="approved">
                                <button type="submit" class="btn btn-success me-2">Approve</button>
                            </form>

                            <!-- Button to trigger reject modal -->
                            <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#rejectModal">Reject</button>

                            <!-- Modal HTML -->
                            <div class="modal fade" id="rejectModal" tabindex="-1" aria-labelledby="rejectModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="rejectModalLabel">Alasan Penolakan</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <form action="{{ route('permintaan.updateStatus', $permintaan->id) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="rejected">
                                                <div class="mb-3">
                                                    <label for="rejectReason" class="form-label">Masukkan alasan penolakan:</label>
                                                    <textarea id="rejectReason" name="reason" class="form-control" rows="3" required></textarea>
                                                </div>
                                                <div class="d-flex justify-content-end">
                                                    <button type="submit" class="btn btn-danger">Reject</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('rejectButton').addEventListener('click', function() {
        var rejectModal = new bootstrap.Modal(document.getElementById('rejectModal'));
        rejectModal.show();
    });
</script>


@endsection
