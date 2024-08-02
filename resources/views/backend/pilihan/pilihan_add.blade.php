@extends('admin.admin_master')
@section('admin')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<style>
    .step-indicator {
        display: flex;
        justify-content: space-between;
        margin-bottom: 20px;
    }
    .step-indicator .step {
        display: flex;
        flex-direction: column;
        align-items: center;
        cursor: pointer;
        position: relative;
    }
    .step-indicator .step .circle {
        width: 40px;
        height: 40px;
        background-color: #e9ecef;
        border-radius: 50%;
        display: flex;
        justify-content: center;
        align-items: center;
        font-weight: bold;
    }
    .step-indicator .step.active .circle {
        background-color: #007bff;
        color: white;
    }
    .step-indicator .step::after {
        content: '';
        position: absolute;
        top: 20px;
        left: 50%;
        transform: translateX(-50%);
        width: 100%;
        height: 2px;
        background-color: #e9ecef;
        z-index: -1;
    }
    .step-indicator .step:first-child::after {
        display: none;
    }
    .step-indicator .step.active + .step::after {
        background-color: #007bff;
    }
</style>

<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Pengajuan Permintaan Barang</h4>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Halaman Pengajuan Permintaan</h4><br><br>
                        
                        <!-- Wizard Steps -->
                        <div id="wizard">
                            <div class="step-indicator">
                                <div class="step active" data-step="1">
                                    <div class="circle">1</div>
                                    <div class="label">Informasi Permintaan</div>
                                </div>
                                <div class="step" data-step="2">
                                    <div class="circle">2</div>
                                    <div class="label">Detail Permintaan</div>
                                </div>
                            </div>

                            <!-- Step 1 -->
                            <div id="step1" class="step-content">
                                <h5>Langkah 1: Informasi Permintaan</h5>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="name" class="form-label">Nama Pengaju</label>
                                            <input type="text" class="form-control" id="name" value="Nama Pengaju" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="date" class="form-label">Tanggal Permintaan</label>
                                            <input class="form-control" name="date" type="date" id="date">
                                            <div id="date_warning" class="form-text text-danger" style="display: none;">
                                                Tanggal tidak boleh kurang dari hari ini.
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label for="textarea" class="form-label mb-1">Catatan</label>
                                            <textarea id="textarea" class="form-control" maxlength="225" rows="3" placeholder="Penjelasan. (Maksimal 225 Karakter)"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Step 2 -->
                            <div id="step2" class="step-content" style="display: none;">
                                <h5>Langkah 2: Detail Permintaan</h5>
                                <div class="row">
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
                                        <table class="table-sm table-bordered" width="100%" style="border-color: #ddd;">
                                            <thead>
                                                <tr>
                                                    <th style="width: 20%;">Tanggal</th>
                                                    <th style="width: 20%;">Nama Barang</th>
                                                    <th style="width: 20%;">Kelompok Barang</th>
                                                    <th style="width: 10%;">Kuantitas</th>
                                                    <th style="width: 20%;">Deskripsi</th>
                                                    <th style="width: 10%;">Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody id="item_table_body">
                                                <!-- Data tabel di sini -->
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <!-- Navigation Buttons -->
                            <div class="mt-4">
                                <button type="button" class="btn btn-secondary" id="prev_btn" style="display: none;">Previous</button>
                                <button type="button" class="btn btn-primary" id="next_btn">Next</button>
                                <button type="submit" class="btn btn-info" id="submit_btn" style="display: none;">Submit</button>
                                <button type="button" class="btn btn-info" id="addMoreButton" style="display: none;">Tambah Lagi</button>
                            </div>
                        </div>
                    </div>
                </div> <!-- end col -->
            </div> <!-- end row -->
        </div>
    </div>
</div>

<script id="document-template" type="text/x-handlebars-template">
    <tr class="delete_add_more_item">
        <td>@{{ date }}</td>
        <td>@{{ barang_nama }}</td>
        <td>@{{ kelompok_nama }}</td>
        <td>@{{ qty_req }} @{{ barang_satuan }}</td>
        <td>@{{ description }}</td>
        <td>
            <i class="btn btn-danger btn-sm fas fa-window-close removeeventmore"></i>
        </td>
    </tr>
</script>

<script type="text/javascript">
    $(document).ready(function() {
        var currentStep = 1;
        var availableQty = 0; // Variabel untuk menyimpan kuantitas barang yang tersedia
        var barang_satuan = ''; // Variabel untuk menyimpan satuan barang
        var today = new Date().toISOString().split('T')[0];
        $('#date').attr('min', today);

        // Fungsi untuk format tanggal
        function formatDate(dateString) {
            var options = { day: 'numeric', month: 'long', year: 'numeric' };
            return new Date(dateString).toLocaleDateString('id-ID', options);
        }

        // Fungsi untuk memeriksa semua kondisi dan mengaktifkan tombol submit jika memenuhi syarat
        function validateForm() {
            var date = $('#date').val();
            var kelompok_id = $('#kelompok_id').val();
            var barang_id = $('#barang_id').val();
            var req_qty = $('#req_qty').val();
            
            if (date && kelompok_id && barang_id && req_qty && req_qty <= availableQty) {
                $('#addMoreButton').prop('disabled', false); // Aktifkan tombol submit
            } else {
                $('#addMoreButton').prop('disabled', true); // Nonaktifkan tombol submit
            }
        }

        // Fungsi untuk menavigasi ke langkah tertentu
        function navigateToStep(step) {
            if (step === 1) {
                $('#step1').show();
                $('#step2').hide();
                $('#prev_btn').hide();
                $('#next_btn').show();
                $('#submit_btn').hide();
                $('#addMoreButton').hide();
                currentStep = 1;
                $('.step[data-step="2"]').removeClass('active');
                $('.step[data-step="1"]').addClass('active');
            } else if (step === 2) {
                $('#step1').hide();
                $('#step2').show();
                $('#prev_btn').show();
                $('#next_btn').hide();
                $('#submit_btn').show();
                $('#addMoreButton').show();
                currentStep = 2;
                $('.step[data-step="1"]').removeClass('active');
                $('.step[data-step="2"]').addClass('active');
            }
        }

        // Ketika tanggal diinput
        $('#date').on('input', function() {
            var selectedDate = $(this).val();
            if (selectedDate < today) {
                $('#date_warning').show(); // Tampilkan peringatan
                $('#next_btn').prop('disabled', true); // Nonaktifkan tombol submit
            } else {
                $('#date_warning').hide(); // Sembunyikan peringatan
                $('#next_btn').prop('disabled', false); // Aktifkan tombol submit
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
                        html += '<option value="' + item.id + '" data-qty="' + item.qty_item + '" data-satuan="' + item.satuan + '">' + item.nama + '</option>';
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
            barang_satuan = selectedOption.data('satuan'); // Ambil satuan barang
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
                $('#addMoreButton').prop('disabled', true); // Nonaktifkan tombol submit
            } else {
                $('#qty_warning').hide(); // Sembunyikan peringatan jika kuantitas permintaan valid
                validateForm(); // Validasi form secara keseluruhan
            }
        });

        // Ketika tombol Tambah lagi diklik
        $('#addMoreButton').on('click', function() {
            var date = $('#date').val();
            var kelompok_id = $('#kelompok_id').val();
            var kelompok_nama = $('#kelompok_id').find('option:selected').text();
            var barang_id = $('#barang_id').val();
            var barang_nama = $('#barang_id').find('option:selected').text();
            var qty_req = $('#req_qty').val();
            var description = $('#textarea').val();

            // Validasi form sebelum menambah baris
            if (kelompok_id == '') {
                $.notify("Kolom kelompok barang diperlukan.", { globalPosition: 'top right', className: 'error' });
                return false;
            }
            if (barang_id == '') {
                $.notify("Kolom nama barang diperlukan.", { globalPosition: 'top right', className: 'error' });
                return false;
            }
            if (qty_req == '') {
                $.notify("Kolom kuantitas permintaan diperlukan.", { globalPosition: 'top right', className: 'error' });
                return false;
            }

            var source = $("#document-template").html(); // Ambil template
            var template = Handlebars.compile(source); // Kompilasi template
            var context = {
                date: formatDate(date), // Format tanggal sebelum menampilkan
                barang_nama: barang_nama,
                kelompok_nama: kelompok_nama,
                qty_req: qty_req,
                barang_satuan: barang_satuan, // Tambahkan satuan ke context
                description: description
            };
            var html = template(context); // Render template dengan data

            $('#item_table_body').append(html); // Menambahkan baris baru ke tabel

            // Reset form setelah menambah baris
            $('#kelompok_id').val('');
            $('#barang_id').html('<option selected disabled>Pilih barang yang ingin diajukan</option>');
            $('#req_qty').val('');
            $('#textarea').val('');
            $('#current_qty').text('Kuantitas barang sekarang: ');
            validateForm(); // Validasi form secara keseluruhan
        });

        // Menghapus baris tabel
        $(document).on("click", ".removeeventmore", function() {
            $(this).closest("tr").remove();
            validateForm(); // Validasi form setelah menghapus baris
        });

        // Navigasi antara langkah menggunakan tombol next dan previous
        $('#next_btn').on('click', function() {
            navigateToStep(2);
        });

        $('#prev_btn').on('click', function() {
            navigateToStep(1);
        });

        // Navigasi antara langkah menggunakan klik pada circle
        $('.step .circle').on('click', function() {
            var step = $(this).parent().data('step');
            navigateToStep(step);
        });
    });
</script>

@endsection
