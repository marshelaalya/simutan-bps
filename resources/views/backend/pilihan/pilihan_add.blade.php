@extends(auth()->user()->role === 'admin' ? 'admin.admin_master' : 'pegawai.pegawai_master')

@section(auth()->user()->role === 'admin' ? 'admin' : 'pegawai')

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/handlebars@4.7.7/dist/handlebars.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="{{ asset('js/app.js') }}"></script>


<style>
    .select2-results__options {
        max-height: 200px; /* Adjust height as needed */
        overflow-y: auto;
    }
    .select2-container--default .select2-results__options {
        max-height: 200px; /* Sesuaikan tinggi sesuai kebutuhan */
        overflow-y: auto;  /* Aktifkan scroll jika opsi melebihi tinggi */
    }
    .step-indicator {
        display: flex;
        margin-bottom: 10px;
    }

    .step-indicator .step {
        display: flex;
        align-items: center;
        cursor: pointer;
        position: relative;
    }

    .step-indicator .step .circle {
        width: 40px;
        height: 40px;
        background-color: #e9ecef;
        border-radius: 0.25rem;
        display: flex;
        justify-content: center;
        align-items: center;
        font-weight: bold;
        color: #000; /* Default text color for circle */
    }

    .step-indicator .step.active .circle {
        background-color: #043277;
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

    .step .label {
        font-size: 1.125rem;
        color: #6c757d; /* Default text color for label */
    }

    .step.active .label {
        color: black; /* Color for active step label */
    }
</style>


<div class="page-content">
    <div class="container-fluid">
        <!-- start page title -->
<div class="row">
    <div class="col-12">
    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
        <h4 class="mb-sm-0 text-info">Pengajuan Permintaan</h4>
    
        <div class="page-title-right">
            <ol class="breadcrumb m-0">
                <li class="breadcrumb-item"><a href="javascript: void(0);">Permintaan</a></li>
                <li class="breadcrumb-item active">Ajukan Permintaan</li>
            </ol>
        </div>
    
    </div>
    </div>
    </div>
    <!-- end page title -->
        <div class="row">
            <div class="col-12">

                        <!-- Wizard Steps -->
                        <div id="wizard">
                            <div class="card">
                                <div class="card-body">
                            <div class="step-indicator">
                                <div class="step active" data-step="1">
                                    <div class="circle">1</div>
                                    <div class="label ms-2 fw-bold">Informasi Permintaan</div>
                                </div>
                                <div>
                                    <i class="mdi mdi-chevron-right mx-3" style="font-size: 30px;"></i>
                                </div>
                                
                                <div class="step" data-step="2">
                                    <div class="circle">2</div>
                                    <div class="label ms-2">Detail Barang Permintaan</div>
                                </div>
                            </div>
                        
                            
                            <!-- Step 1 -->
                            <div id="step1" class="step-content">
                                {{-- <h5 class="mb-4">Langkah 1: Informasi Permintaan</h5> --}}
                                <hr class="border border-secondary" style="border-width: 0.2px;">
                                <div class="row g-3">
                                    <div class="col-sm-6">
                                        <div class="mb-3">
                                            <label for="name" class="form-label text-info">Nama Pegawai</label>
                                            <input type="text" class="form-control" id="name" value="{{ Auth::user()->name }}" readonly>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="mb-3">
                                            <label for="date" class="form-label text-info">Tanggal Permintaan</label>
                                            <input class="form-control" name="date" type="date" id="date">
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="mb-3">
                                            <label for="textarea" class="form-label mb-1 text-info">Catatan</label>
                                            <textarea id="textarea" class="form-control" maxlength="225" rows="3" placeholder="Penjelasan. (Maksimal 225 Karakter)"></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div id="warning_message" class="text-danger" style="display: none;">
                                    <p>Semua kolom harus diisi. Harap isi tanggal dan deskripsi sebelum melanjutkan.</p>
                                </div>
                                <div class="mt-4 d-flex justify-content-end">
                                    <button type="button" class="btn btn-info" id="next_btn_step1">Next&nbsp;&nbsp;<i class=" mdi mdi-arrow-right font-size-16 text-white align-middle"></i></button>
                                </div>
                                
                            </div>
                        
                            <!-- Step 2 -->
                            <div id="step2" class="step-content" style="display: none;">
                                {{-- <h5>Langkah 2: Detail Permintaan</h5> --}}
                                <hr class="border border-secondary" style="border-width: 0.2px;">
                                
                                <div class="row g-3 mb-3">
                                    <div style="-webkit-box-flex:0; -ms-flex:0 0 auto; flex:0 0 auto; width:20%"">
                                        <div>
                                            <label for="kelompok_id" class="form-label text-info">Kelompok Barang</label>
                                            <select name="kelompok_id" class="form-select" id="kelompok_id" aria-label="Pilih Barang">
                                                <option selected disabled>Kelompok Barang</option>
                                                @foreach($kelompok as $kel)
                                                    <option value="{{ $kel->id }}">{{ $kel->nama }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div style="-webkit-box-flex:0; -ms-flex:0 0 auto; flex:0 0 auto; width:25%">
                                        <div>
                                            <label for="barang_id" class="form-label text-info">Nama Barang</label>
                                            <select name="barang_id" class="form-select" id="barang_id" aria-label="Pilih Barang">
                                                <option selected disabled>Pilih barang yang ingin diajukan</option>
                                            </select>
                                        </div>
                                    </div>
                                    
                                    <div style="-webkit-box-flex:0; -ms-flex:0 0 auto; flex:0 0 auto; width:20%">
                                        <div>
                                            <label for="req_qty" class="form-label text-info">Kuantitas Permintaan</label>
                                            <input class="form-control" name="req_qty" type="text" id="req_qty">
                                            <div id="qty_warning" class="form-text text-danger" style="display: none;">
                                                Kuantitas permintaan tidak boleh lebih dari kuantitas barang sekarang.
                                            </div>
                                            <span id="current_qty" class="form-text">Kuantitas barang sekarang: </span>
                                        </div>
                                    </div>

                                    <div style="-webkit-box-flex:0; -ms-flex:0 0 auto; flex:0 0 auto; width:20%">
                                        <div>
                                            <label for="satuan_id" class="form-label text-info">Satuan</label>
                                            <select name="satuan_id" class="form-select" id="satuan_id" aria-label="Pilih Satuan">
                                                <option selected disabled>Pilih Satuan</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-sm-2 d-flex justify-content-end align-items-start w-auto" style="margin-top:2.8rem">
                                        <button type="button" class="btn btn-info" id="addMoreButton">
                                            <i class="mdi mdi-plus"></i> <span>Tambah</span>
                                        </button>
                                    </div>
                                    {{-- <button type="button" class="btn btn-info" id="addMoreButton" style="display: inline-block;width: auto; height:auto;">Tambah Lagi</button> --}}
                                </div>
                        
                                <!-- Tabel -->
                                <label for="mainForm" class="text-info"> Tabel Permintaan Barang</label>
                                    <form id="mainForm" method="post" action="{{ route('pilihan.store') }}">
                                        @csrf
                                        <div class="table-responsive">
                                            <table class="table table-centered mb-0 align-middle table-hover table-nowrap">
                                                <thead class="table-light">
                                                    <tr>
                                                    <th style="width: 23%;">Kelompok Barang</th>
                                                    <th>Nama Barang</th>
                                                    <th class="text-center" style="width: 1%;">Kuantitas</th>
                                                    <th class="text-center" style="width: 1%;">Aksi</th>
                                                    </tr>
                                                    
                                                </thead>
                                            <tbody id="table-body">
                                                <!-- Data tabel di sini -->
                                                <tr id="no-data-row">
                                                    <td colspan="4" class="text-center">Tidak ada barang terpilih</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        <input type="hidden" name="table_data" id="table_data" value="">
                                        <input type="hidden" name="permintaan_id" id="permintaan_id" value="">
                                        <input type="hidden" id="hidden_date" name="hidden_date">
                                        <input type="hidden" id="hidden_description" name="hidden_description">   
                        
                                        <!-- Navigation Buttons -->
                                        <div class="mt-4 d-flex justify-content-between">
                                            <button type="button" class="btn btn-info" id="prev_btn"><i class=" mdi mdi-arrow-left font-size-16 text-white align-middle"></i>&nbsp;&nbsp;Previous</button>
                                            <button type="submit" class="btn btn-success" id="submit_btn">Submit</button>
                                            
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

<script id="document-template" type="text/x-handlebars-template">
    <tr class="delete_add_more_item">
        <td>@{{ kelompok_nama }}</td>
        <td>@{{ barang_nama }}</td>
        <td class="text-center">@{{ qty_req }} @{{ barang_satuan }}</td>
        <td style="text-align: center; vertical-align: middle;">
            <a href="{{ route('dashboard') }}" class="btn bg-warning btn-sm">
                <i class="fas fa-edit" style="color: #ca8a04"></i>
            </a>
            <a href="javascript:void(0);" class="btn bg-danger btn-sm delete-button" data-id="@{{ id }}">
                <i class="fas fa-trash text-danger"></i>
            </a>
        </td>
    </tr>
</script>


<script>
// Event delegation to handle dynamically added rows
$(document).on('click', '.delete-button', function() {
    // Remove the table row that contains the clicked delete button
    $(this).closest('tr').remove();
    
    // Optionally, you can handle additional actions such as updating the backend or notifying the user
});
</script>

<script type="text/javascript">
    $(document).ready(function() {
    var availableQty = 0; // Variabel untuk menyimpan kuantitas barang yang tersedia
    var barang_satuan = ''; // Variabel untuk menyimpan satuan barang
    var today = new Date().toISOString().split('T')[0];

    function validateForm() {
        var date = $('#date').val();
        var kelompok_id = $('#kelompok_id').val();
        var barang_id = $('#barang_id').val();
        var req_qty = $('#req_qty').val();
        
        if (date && kelompok_id && barang_id && req_qty && req_qty <= availableQty) {
            $('#addMoreButton').prop('disabled', false);
        } else {
            $('#addMoreButton').prop('disabled', true);
        }
    }

    $('#date').on('input', function() {
        var selectedDate = $(this).val();
        $('#date_warning').hide();
        $('#addMoreButton').prop('disabled', false);
        validateForm();
    });

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
                $('#barang_id').html(html).trigger('change'); // Use trigger to update Select2
                $('#req_qty').val('');
                $('#current_qty').text('Kuantitas barang sekarang: ');
                $('#qty_warning').hide();
                validateForm();
            },
            error: function(xhr) {
                console.error('An error occurred:', xhr.responseText);
            }
        });
    });

    $('#barang_id').on('change', function() {
        var selectedOption = $(this).find('option:selected');
        var barang_id = $(this).val();
        availableQty = parseFloat(selectedOption.data('qty')) || 0; // Pastikan availableQty adalah angka
        barang_satuan = selectedOption.data('satuan');

        $('#current_qty').text('Kuantitas barang sekarang: ' + availableQty);
        $('#req_qty').val('');
        $('#qty_warning').hide();

        if (barang_id) {
            $.ajax({
                url: "{{ route('get-satuan') }}",
                type: "GET",
                data: { barang_id: barang_id },
                success: function(data) {
                    var html = '<option value="">Pilih Satuan</option>';
                    $.each(data, function(key, item) {
                        html += '<option value="' + item.satuan_id + '">' + item.nama + '</option>';
                    });
                    $('#satuan_id').html(html);
                },
                error: function(xhr) {
                    console.error('An error occurred:', xhr.responseText);
                }
            });
        } else {
            $('#satuan_id').html('<option value="">Pilih Satuan</option>');
        }

        validateForm();
    });

    $('#req_qty').on('input', function() {
        var requestedQty = parseFloat($(this).val()) || 0; // Pastikan requestedQty adalah angka
        var currentQty = parseFloat($('#current_qty').text().replace('Kuantitas barang sekarang: ', '')) || 0;

        if (requestedQty > currentQty) {
            $('#qty_warning').show();
            $('#addMoreButton').prop('disabled', true);
        } else {
            $('#qty_warning').hide();
            validateForm();
        }
    });

    $('#addMoreButton').on('click', function() {
        var date = $('#date').val();
        var kelompok_id = $('#kelompok_id').val();
        var kelompok_nama = $('#kelompok_id').find('option:selected').text();
        var barang_id = $('#barang_id').val();
        var barang_nama = $('#barang_id').find('option:selected').text();
        var qty_req = $('#req_qty').val();
        var description = $('#textarea').val();

        if (date == '' || kelompok_id == '' || barang_id == '' || qty_req == '') {
            $.notify("Semua kolom harus diisi.", { globalPosition: 'top right', className: 'error' });
            return false;
        }

        var source = $("#document-template").html();
        var template = Handlebars.compile(source);
        var context = {
            date: date,
            barang_nama: barang_nama,
            kelompok_nama: kelompok_nama,
            qty_req: qty_req,
            barang_satuan: barang_satuan,
            description: description
        };
        var html = template(context);

        $('#table-body').append(html);
        $('#kelompok_id').val('');
        $('#barang_id').html('<option selected disabled>Pilih barang yang ingin diajukan</option>');
        $('#req_qty').val('');
        $('#textarea').val('');
        $('#current_qty').text('Kuantitas barang sekarang: ');
        validateForm();
    });

    $(document).on("click", ".removeeventmore", function() {
        $(this).closest("tr").remove();
        validateForm();
    });

    $('#mainForm').on('submit', function(e) {
        e.preventDefault(); // Prevent default form submission

        var date = $('#date').val();
        var description = $('#textarea').val();

        $('#hidden_date').val(date);
        $('#hidden_description').val(description);

        var tableData = [];
        $('#table-body tr').each(function() {
            var kelompok_nama = $(this).find('td:eq(0)').text();
            var barang_nama = $(this).find('td:eq(1)').text();
            var qty_req = $(this).find('td:eq(2)').text();

            tableData.push({
                date: $('#hidden_date').val(),
                kelompok_nama: kelompok_nama,
                barang_nama: barang_nama,
                qty_req: qty_req,
                description: $('#hidden_description').val()
            });
        });

        $('#table_data').val(JSON.stringify(tableData));
        $(this).off('submit').submit();
    });

    function validateStep1() {
        var date = $('#date').val();
        var description = $('#textarea').val();
        
        console.log('Validating Step 1:', date, description); // Debugging
        
        if (date && description) {
            $('#next_btn_step1').removeClass('disabled').prop('disabled', false);
            $('#warning_message').hide(); 
            return true;
        } else {
            $('#next_btn_step1').addClass('disabled').prop('disabled', true);
            $('#warning_message').show(); 
            return false;
        }
    }

    $('#date, #textarea').on('input', validateStep1);

    function navigateToStep(step) {
        $('.step-content').hide();
        $('#step' + step).show();
        $('.step').removeClass('active');
        $('.step[data-step="' + step + '"]').addClass('active');
        
        $('#prev_btn').toggle(step > 1);
        $('#next_btn_step1').toggle(step < 2);
        $('#submit_btn').toggle(step == 2);
        $('#addMoreButton').toggle(step == 2);

        $('.step .circle').each(function() {
            var circleStep = $(this).parent().data('step');
            $(this).toggleClass('completed', circleStep < step);
        });
    }

    $('#next_btn_step1').on('click', function() {
        if (validateStep1()) {
            navigateToStep(2);
        } else {
            console.log('Validation failed on Step 1');
        }
    });

    $('#prev_btn').on('click', function() {
        navigateToStep(1);
    });

    $('.step .circle').on('click', function() {
        var step = $(this).parent().data('step');
        
        console.log('Circle clicked:', step);

        if (step === 1) {
            if (validateStep1()) {
                navigateToStep(step);
            }
        } else {
            var currentStep = $('.step.active').data('step');
            if (currentStep < step) {
                if (validateStep1()) {
                    navigateToStep(step);
                }
            } else {
                navigateToStep(step);
            }
        }
    });

    navigateToStep(1);
});
</script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Function to check if the table is empty and manage the "No Data" row
        function checkIfTableIsEmpty() {
            const tableBody = document.getElementById('table-body');
            const noDataRow = document.getElementById('no-data-row');

            // Check if there are no rows in the table body (excluding the no-data row)
            if (tableBody.children.length === 1 && tableBody.children[0].id === 'no-data-row') {
                noDataRow.style.display = '';
            } else {
                noDataRow.style.display = 'none';
            }
        }

        // Initial check on page load
        checkIfTableIsEmpty();

        // Event listener for removing a row
        document.getElementById('table-body').addEventListener('click', function (e) {
            if (e.target && e.target.matches('i.removeeventmore')) {
                e.target.closest('tr').remove();
                checkIfTableIsEmpty();
            }
        });

        // Add row logic
        $('#addMoreButton').on('click', function() {
            const source = $("#document-template").html();
            const template = Handlebars.compile(source);

            const context = {
                date: $('#date').val(), 
                barang_nama: $('#barang_id option:selected').text(),
                kelompok_nama: $('#kelompok_id option:selected').text(),
                qty_req: $('#req_qty').val(),
                barang_satuan: $('#barang_id option:selected').data('satuan'),
                description: $('#textarea').val()
            };

            const html = template(context);

            $('#table-body').append(html);
            checkIfTableIsEmpty();
            
            // Reset form fields
            $('#kelompok_id').val('');
            $('#barang_id').html('<option selected disabled>Pilih barang yang ingin diajukan</option>');
            $('#req_qty').val('');
            $('#current_qty').text('Kuantitas barang sekarang: ');

            validateForm();
        });
    });
</script>

<script>
    $(document).ready(function() {
    $('#barang_id').select2({
        placeholder: "Pilih barang yang ingin diajukan",
        width: '100%',
        maximumSelectionLength: 5,
        dropdownCssClass: 'bigdrop', // Class for custom dropdown styling
    });

    var availableQty = 0; // Variabel untuk menyimpan kuantitas barang yang tersedia
    var barang_satuan = ''; // Variabel untuk menyimpan satuan barang
    var today = new Date().toISOString().split('T')[0];
    $('#date').attr('min', today);

    // Function to validate form
    function validateForm() {
        var date = $('#date').val();
        var kelompok_id = $('#kelompok_id').val();
        var barang_id = $('#barang_id').val();
        var req_qty = $('#req_qty').val();

        if (date && kelompok_id && barang_id && req_qty && req_qty <= availableQty) {
            $('#addMoreButton').prop('disabled', false);
        } else {
            $('#addMoreButton').prop('disabled', true);
        }
    }

    $('#date').on('input', function() {
        var selectedDate = $(this).val();
        if (selectedDate < today) {
            $('#date_warning').show();
            $('#addMoreButton').prop('disabled', true);
        } else {
            $('#date_warning').hide();
            validateForm();
        }
    });

    // Event handlers and other logic here...

    // Example AJAX call to populate barang_id options
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
                $('#barang_id').html(html).trigger('change'); // Use trigger to update Select2
                $('#req_qty').val('');
                $('#current_qty').text('Kuantitas barang sekarang: ');
                $('#qty_warning').hide();
                validateForm();
            },
            error: function(xhr) {
                console.error('An error occurred:', xhr.responseText);
            }
        });
    });
});

</script>
<!-- Select2 CSS -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />

<!-- Select2 JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

    <script>
        $(document).ready(function() {
    $('#barang_id').select2({
        placeholder: "Pilih barang yang ingin diajukan",
        width: '100%',
        minimumResultsForSearch: 0 // Menampilkan search box
    });
});

    </script>

    <script>
        $(document).ready(function() {
    $('.js-example-basic-single').select2();
    });
    </script>

    <script>
        $(document).ready(function() {
            // Inisialisasi Select2 pada elemen #barang_id
            $('#barang_id').select2();
        
            // Event handler untuk perubahan pada #kelompok_id
            $('#kelompok_id').on('change', function() {
                var kelompok_id = $(this).val();
                $.ajax({
                    url: "{{ route('get-category') }}", // Sesuaikan URL dengan rute yang benar
                    type: "GET",
                    data: { kelompok_id: kelompok_id },
                    success: function(data) {
                        var html = '<option value="">Pilih barang yang ingin diajukan</option>';
                        $.each(data, function(key, item) {
                            html += '<option value="' + item.id + '" data-qty="' + item.qty_item + '" data-satuan="' + item.satuan + '">' + item.nama + '</option>';
                        });
                        $('#barang_id').html(html).trigger('change'); // Gunakan trigger untuk memperbarui Select2
                    },
                    error: function(xhr) {
                        console.error('An error occurred:', xhr.responseText);
                    }
                });
            });
        
            // Event handler untuk perubahan pada #barang_id
            $('#barang_id').on('change', function() {
                var selectedOption = $(this).find('option:selected');
                var availableQty = selectedOption.data('qty');
                var barang_satuan = selectedOption.data('satuan');
                $('#current_qty').text('Kuantitas barang sekarang: ' + availableQty);
                $('#req_qty').val('');
                $('#qty_warning').hide();
                validateForm();
            });
        
            // Fungsi untuk memvalidasi form
            function validateForm() {
                var date = $('#date').val();
                var kelompok_id = $('#kelompok_id').val();
                var barang_id = $('#barang_id').val();
                var req_qty = $('#req_qty').val();
        
                if (date && kelompok_id && barang_id && req_qty && req_qty <= availableQty) {
                    $('#addMoreButton').prop('disabled', false);
                } else {
                    $('#addMoreButton').prop('disabled', true);
                }
            }
        
            // Validasi form saat input berubah
            $('#req_qty').on('input', function() {
                var requestedQty = $(this).val();
                if (requestedQty > availableQty) {
                    $('#qty_warning').show();
                    $('#addMoreButton').prop('disabled', true);
                } else {
                    $('#qty_warning').hide();
                    validateForm();
                }
            });
        
            // Tombol Tambah Barang
            $('#addMoreButton').on('click', function() {
                var date = $('#date').val();
                var kelompok_id = $('#kelompok_id').val();
                var kelompok_nama = $('#kelompok_id').find('option:selected').text();
                var barang_id = $('#barang_id').val();
                var barang_nama = $('#barang_id').find('option:selected').text();
                var qty_req = $('#req_qty').val();
                var description = $('#textarea').val();
        
                if (date == '' || kelompok_id == '' || barang_id == '' || qty_req == '') {
                    $.notify("Semua kolom harus diisi.", { globalPosition: 'top right', className: 'error' });
                    return false;
                }
        
                var source = $("#document-template").html();
                var template = Handlebars.compile(source);
                var context = {
                    date: date, // Menampilkan tanggal yang diinput pengguna
                    barang_nama: barang_nama,
                    kelompok_nama: kelompok_nama,
                    qty_req: qty_req,
                    barang_satuan: barang_satuan,
                    description: description
                };
                var html = template(context);
        
                $('#table-body').append(html);
        
                // Reset form fields
                $('#kelompok_id').val('');
                $('#barang_id').html('<option selected disabled>Pilih barang yang ingin diajukan</option>');
                $('#req_qty').val('');
                $('#current_qty').text('Kuantitas barang sekarang: ');
                validateForm();
            });
        });
        </script>
        

<script src="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>

@endsection