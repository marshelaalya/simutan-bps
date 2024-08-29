@extends(auth()->user()->role === 'admin' ? 'admin.admin_master' : 
         (auth()->user()->role === 'supervisor' ? 'supervisor.supervisor_master' : 
         'pegawai.pegawai_master'))

@section(auth()->user()->role === 'admin' ? 'admin' : 
         (auth()->user()->role === 'supervisor' ? 'supervisor' : 'pegawai'))

<head>
    <title>
        Edit Permintaan | SIMUTAN
    </title>
</head>

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
                    <h4 class="mb-sm-0 text-info">Edit Permintaan</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Permintaan</a></li>
                            <li class="breadcrumb-item active">Edit Permintaan</li>
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
                                <hr class="border border-secondary" style="border-width: 0.2px;">
                                <div class="row g-3">
                                        <div class="col-sm-6">
                                            <div class="mb-3">
                                                <label for="name" class="form-label text-info">Nama Pegawai</label>
                                                <input type="text" class="form-control" id="name" value="{{ $pilihan->first()->created_by }}" readonly>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="mb-3">
                                                <label for="date" class="form-label text-info">Tanggal Permintaan</label>
                                                <input class="form-control" name="date" type="date" id="date" value="{{ $pilihan->first()->date }}">
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="mb-3">
                                                <label for="textarea" class="form-label mb-1 text-info">Catatan</label>
                                                <textarea id="textarea" name="description" class="form-control" maxlength="225" rows="3" placeholder="Penjelasan. (Maksimal 225 Karakter)">{{ $pilihan->first()->description }}</textarea>
                                            </div>
                                        </div>
                                        
                                </div>
                                <div id="warning_message" class="text-danger" style="display: none;">
                                    <p>Semua kolom harus diisi. Harap isi tanggal dan deskripsi sebelum melanjutkan.</p>
                                </div>
                                <div class="mt-4 d-flex justify-content-end">
                                    <button type="button" class="btn btn-info" id="next_btn_step1">Next&nbsp;&nbsp;<i class="mdi mdi-arrow-right font-size-16 text-white align-middle"></i></button>
                                </div>
                            </div>

                            <!-- Step 2 -->
                            <div id="step2" class="step-content" style="display: none;">
                                <hr class="border border-secondary" style="border-width: 0.2px;">
                                
                                <div class="row g-3 mb-3">
                                    <div class="col-sm-3"  style="-webkit-box-flex:0; -ms-flex:0 0 auto; flex:0 0 auto; width:20%">
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
                                    <div style="-webkit-box-flex:0; -ms-flex:0 0 auto; flex:0 0 auto; width:29%">
                                        <div>
                                            <label for="barang_id" class="form-label text-info">Nama Barang</label>
                                            <select name="barang_id" class="form-select" id="barang_id" aria-label="Pilih Barang">
                                                <option selected disabled>Pilih barang yang ingin diajukan</option>
                                            </select>
                                        </div>
                                    </div>
                                    
                                    <div style="-webkit-box-flex:0; -ms-flex:0 0 auto; flex:0 0 auto; width:20%">
                                        <div>
                                            <label for="current_qty" class="form-label text-info">Kuantitas Barang Sekarang</label>
                                            <input type="text" id="current_qty" class="form-control" readonly />
                                        </div>
                                    </div>
                                    <div class="col-sm-3"  style="-webkit-box-flex:0; -ms-flex:0 0 auto; flex:0 0 auto; width:20%">
                                        <div>
                                            <label for="req_qty" class="form-label text-info">Kuantitas Permintaan</label>
                                            <input class="form-control" name="req_qty" type="text" id="req_qty">
                                            <div id="qty_warning" class="form-text text-danger" style="display: none;">
                                                Kuantitas permintaan tidak boleh lebih dari kuantitas barang sekarang.
                                            </div>
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
                                <form id="mainForm" method="post" action="{{ route('permintaan.update', $id) }}">
                                    @csrf
                                    @method('PUT')
                                    <!-- Hidden Fields -->
                                    <input type="hidden" name="date" id="hidden_date">
                                    <input type="hidden" name="description" id="hidden_description">
                                    <input type="hidden" name="table_data" id="table_data" value="">
                                    <input type="hidden" name="permintaan_id" id="permintaan_id" value="{{ $id }}">

                                    <div class="table-responsive">
                                        <table class="table table-centered mb-0 align-middle table-hover table-nowrap">
                                            <thead class="table-light">
                                                <tr>
                                                    <th style="width: 23%;">Kelompok Barang</th>
                                                    <th>Nama Barang</th>
                                                    <th class="text-center" style="width: 1%;">Kuantitas</th>
                                                    <th class="text-center" style="width: 1%;">Satuan</th>
                                                    <th class="text-center" style="width: 1%;">Aksi</th>
                                                    </tr>
                                            </thead>
                                            <tbody id="table-body">
                                                @forelse ($pilihan as $item)
                                                    <tr class="delete_add_more_item">
                                                        <td>{{ optional($barang->find($item->barang_id))->kelompok->nama ?? 'N/A' }}</td>
                                                        <td>{{ optional($barang->find($item->barang_id))->nama ?? 'N/A' }}</td>
                                                        <td class="text-center">{{ $item->req_qty }}</td>
                                                        <td class="text-center"> {{ optional($barang->find($item->barang_id))->satuan ?? 'N/A' }}</td>
                                                        <td style="text-align: center; vertical-align: middle;">
                                                            <button type="button" class="btn bg-danger btn-sm delete-button">
                                                                <i class="fas fa-trash text-danger"></i>
                                                            </button>
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="4" class="text-center">Tidak ada data</td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="mt-4 d-flex justify-content-between">
                                        <button type="button" class="btn btn-info" id="prev_btn_step2">
                                            <i class="mdi mdi-arrow-left font-size-16 text-white align-middle"></i>&nbsp;&nbsp;Back
                                        </button>
                                        <button type="submit" class="btn btn-info">Submit</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- end Wizard Steps -->
            </div>
        </div>
    </div>
</div>

<script id="document-template" type="text/x-handlebars-template">
    <tr class="delete_add_more_item">
        <td>@{{ kelompok_nama }}</td>
        <td>@{{ barang_nama }}</td>
        <td class="text-center">@{{ qty_req }}</td>
        <td class="text-center">@{{ barang_satuan }}</td>
        <td style="text-align: center; vertical-align: middle;">
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
        var availableQty = 0;
        var barang_satuan = '';

        function validateForm() {
            var date = $('#date').val();
            var kelompok_id = $('#kelompok_id').val();
            var barang_id = $('#barang_id').val();
            var req_qty = $('#req_qty').val();
            var description = $('#textarea').val();

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
// Fungsi untuk menangani perubahan pada pilihan barang
$('#barang_id').on('change', function() {
    var barangId = $(this).val();
    
    if (barangId) {
        $.ajax({
            url: "{{ route('get-satuan') }}",
            type: "GET",
            data: { barang_id: barangId },
            success: function(data) {
                if (data.error) {
                    console.error(data.error);
                    return;
                }

                // Update UI
                barangSatuan = data.satuan;
                availableQty = data.qty_item;
                $('#current_qty').val(availableQty);
                $('#req_qty').val('');
                $('#qty_warning').hide();
                validateForm();
            },
            error: function(xhr) {
                console.error('An error occurred:', xhr.responseText);
            }
        });
    }
});


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

        $('#addMoreButton').on('click', function() {
            const source = $("#document-template").html();
    const template = Handlebars.compile(source);

    const context = {
        date: $('#date').val(),
        barang_nama: $('#barang_id option:selected').text(),
        kelompok_nama: $('#kelompok_id option:selected').text(),
        qty_req: $('#req_qty').val(),
        barang_satuan: barangSatuan,
        description: $('#textarea').val()
    };

    const html = template(context);
    $('#table-body').append(html);
    checkIfTableIsEmpty();
    
    // Reset form fields
    $('#kelompok_id').val('');
    $('#current_qty').val('');
    $('#barang_id').html('<option selected disabled>Pilih barang yang ingin diajukan</option>');
    $('#req_qty').val('');
    $('#current_qty').text('');
    validateForm();
});


        $(document).on("click", ".removeeventmore", function() {
            $(this).closest("tr").remove();
            validateForm();
        });

        $('#mainForm').on('submit', function(e) {
            e.preventDefault();

            var date = $('#date').val();
            var description = $('#textarea').val();

            $('#hidden_date').val(date);
            $('#hidden_description').val(description);

            var tableData = [];
            $('#table-body tr').each(function() {
                var kelompok_nama = $(this).find('td:eq(0)').text();
                var barang_nama = $(this).find('td:eq(1)').text();
                var qty_req = $(this).find('td:eq(2)').text();
                var barang_satuan = $(this).find('td:eq(3)').text().trim();

                tableData.push({
                    date: $('#hidden_date').val(),
                    kelompok_nama: kelompok_nama,
                    barang_nama: barang_nama,
                    qty_req: qty_req,
                    barang_satuan: barang_satuan,
                    description: $('#hidden_description').val()
                });
            });

            $('#table_data').val(JSON.stringify(tableData));
            $(this).off('submit').submit();
        });

        function validateStep1() {
            var date = $('#date').val();
            var description = $('#textarea').val();

            if (date && description) {
                $('#next_btn_step1').removeClass('disabled').prop('disabled', false);
                $('#warning_message').hide();
                return true;
            } else {
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

            $('#prev_btn_step2').toggle(step > 1);
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

        $('#prev_btn_step2').on('click', function() {
            navigateToStep(1);
        });

        $('.step .circle').on('click', function() {
            var step = $(this).parent().data('step');

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


@endsection
