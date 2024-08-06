@extends('admin.admin_master')

@section('admin')

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">


<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0 text-info">Approval Permintaan</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('permintaan.all') }}">Permintaan</a></li>
                            <li class="breadcrumb-item active">Approval</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <!-- Detail Permintaan -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Informasi Permintaan</h5>
                        <p><strong>Nomor Permintaan     :</strong> {{ $permintaan->no_permintaan }}</p>
                        <p><strong>Nama Pengaju         :</strong> {{ $permintaan->user->name }}</p>
                        <p><strong>Tanggal Permintaan:</strong> {{ $permintaan->pilihan->first()->date }}</p>
                        <p><strong>Deskripsi:</strong> {{ $permintaan->pilihan->first()->description }}</p>

                        <!-- Tabel Barang Permintaan -->
                        <h5 class="card-title mt-4">Detail Barang</h5>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Nama Barang</th>
                                    <th>Jumlah</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($pilihan as $item)
                                    <tr>
                                        <td>{{ $item->barang->nama }}</td>
                                        <td>{{ $item->req_qty }} {{ $item->barang->satuan }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <!-- Form Approval dan Reject -->
                        <div class="mt-4">
                            <!-- Form Reject -->
                            <form id="rejectFormPlaceholder" style="display: none;">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="rejected">
                            </form>
                            <button type="button" id="rejectButton" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#rejectModal">Reject</button>                            

                            <!-- Form Approval -->
                            <form action="{{ route('permintaan.updateStatus', $permintaan->id) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="approved by admin">
                                <button type="submit" class="btn btn-success">Approve</button>
                            </form>

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
                                                <input type="hidden" name="status" value="rejected by admin">
                                                <div class="mb-3">
                                                    <label for="rejectReason" class="form-label">Masukkan alasan penolakan:</label>
                                                    <textarea id="rejectReason" name="reason" class="form-control" rows="3" required></textarea>
                                                </div>
                                                <button type="submit" class="btn btn-danger">Reject</button>
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
    // JavaScript to handle Reject button and modal
    document.getElementById('rejectButton').addEventListener('click', function() {
        // Show the modal
        var rejectModal = new bootstrap.Modal(document.getElementById('rejectModal'));
        rejectModal.show();
    });



</script>

@endsection
