@extends('admin.admin_master')
@section('admin')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Halaman Pengajuan Permintaan</h4><br><br>
                        
                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="date" class="form-label">Tanggal</label>
                                    <input class="form-control" name="date" type="date" id="date">
                                    <div id="date_warning" class="form-text text-danger" style="display: none;">
                                        Tanggal tidak boleh kurang dari hari ini.
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="kelompok_id" class="form-label">Kelompok Barang</label>
                                    <select name="kelompok_id" class="form-select" id="kelompok_id" aria-label="Pilih Barang">
                                        <option selected disabled>Kelompok Barang</option>
                                        @foreach($kelompok as $kel)
                                            <option value="{{ $kel->id }}">{{ $kel->nama }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="barang_id" class="form-label">Nama Barang</label>
                                    <select name="barang_id" class="form-select" id="barang_id" aria-label="Pilih Barang">
                                        <option selected disabled>Pilih barang yang ingin diajukan</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="req_qty" class="form-label">Kuantitas Permintaan</label>
                                    <input class="form-control" name="req_qty" type="text" id="req_qty">
                                    <div id="qty_warning" class="form-text text-danger" style="display: none;">
                                        Kuantitas permintaan tidak boleh lebih dari kuantitas barang sekarang.
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="mb-3">
                                    <span id="current_qty" class="form-text">Kuantitas barang sekarang: </span>
                                </div>
                            </div>

                            <div class="col-md-12 mt-3">
                                <div class="mb-3">
                                    <label for="textarea" class="form-label mb-1">Deskripsi</label>
                                    <textarea id="textarea" class="form-control" maxlength="225" rows="3" placeholder="Penjelasan. (Maksimal 225 Karakter)"></textarea>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="submit" class="form-label">&nbsp;</label>
                                    <input type="submit" class="btn btn-primary waves-effect waves-light" id="submit_btn" value="Tambah Lagi">
                                </div>
                            </div>
                        </div> {{-- End Row --}}
                    </div>
                </div>
            </div> <!-- end col -->
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        var availableQty = 0; // Variabel untuk menyimpan kuantitas barang yang tersedia
        var today = new Date().toISOString().split('T')[0];
        $('#date').attr('min', today);

        // Fungsi untuk memeriksa semua kondisi dan mengaktifkan tombol submit jika memenuhi syarat
        function validateForm() {
            var date = $('#date').val();
            var kelompok_id = $('#kelompok_id').val();
            var barang_id = $('#barang_id').val();
            var req_qty = $('#req_qty').val();
            
            // Memeriksa apakah semua input yang diperlukan diisi dan valid
            if (date && kelompok_id && barang_id && req_qty && req_qty <= availableQty) {
                $('#submit_btn').prop('disabled', false); // Aktifkan tombol submit
            } else {
                $('#submit_btn').prop('disabled', true); // Nonaktifkan tombol submit
            }
        }

        // Ketika tanggal diinput
        $('#date').on('input', function() {
            var selectedDate = $(this).val();
            if (selectedDate < today) {
                $('#date_warning').show(); // Tampilkan peringatan
                $('#submit_btn').prop('disabled', true); // Nonaktifkan tombol submit
            } else {
                $('#date_warning').hide(); // Sembunyikan peringatan
                validateForm(); // Validasi form secara keseluruhan
            }
        });

        // Ketika kelompok barang dipilih
        $('#kelompok_id').on('change', function() {
            var kelompok_id = $(this).val();
            $.ajax({
                url: "{{ route('get-category') }}",
                type: "GET",
                data: { kelompok_id: kelompok_id },
                success: function(data) {
                    var html = '<option value="">Pilih barang yang ingin diajukan</option>';
                    $.each(data, function(key, item) {
                        html += '<option value="' + item.id + '" data-qty="' + item.qty_item + '">' + item.nama + '</option>';
                    });
                    $('#barang_id').html(html);
                    $('#req_qty').val(''); // Reset kuantitas permintaan
                    $('#current_qty').text('Kuantitas barang sekarang: ');
                    $('#qty_warning').hide(); // Sembunyikan peringatan
                    validateForm(); // Validasi form secara keseluruhan
                },
                error: function(xhr) {
                    console.error('An error occurred:', xhr.responseText);
                }
            });
        });

        // Ketika barang dipilih
        $('#barang_id').on('change', function() {
            var selectedOption = $(this).find('option:selected');
            availableQty = selectedOption.data('qty');
            $('#current_qty').text('Kuantitas barang sekarang: ' + availableQty);
            $('#req_qty').val(''); // Reset kuantitas permintaan
            $('#qty_warning').hide(); // Sembunyikan peringatan
            validateForm(); // Validasi form secara keseluruhan
        });

        // Ketika kuantitas permintaan diinput
        $('#req_qty').on('input', function() {
            var requestedQty = $(this).val();
            if (requestedQty > availableQty) {
                $('#qty_warning').show(); // Tampilkan peringatan jika kuantitas permintaan melebihi kuantitas tersedia
                $('#submit_btn').prop('disabled', true); // Nonaktifkan tombol submit
            } else {
                $('#qty_warning').hide(); // Sembunyikan peringatan jika kuantitas permintaan valid
                validateForm(); // Validasi form secara keseluruhan
            }
        });
    });
</script>

@endsection
