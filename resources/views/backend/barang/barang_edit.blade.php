@extends(auth()->user()->role === 'admin' ? 'admin.admin_master' : (auth()->user()->role === 'supervisor' ? 'supervisor.supervisor_master' : 'pegawai.pegawai_master'))
@section(auth()->user()->role === 'admin' ? 'admin' : (auth()->user()->role === 'supervisor' ? 'supervisor' : 'pegawai'))

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Halaman Edit Barang</h4><br><br>

                        <form method="post" action="{{ route('barang.update', $barang->id) }}" id="myForm" enctype="multipart/form-data">
                            @csrf
                            @method('POST') <!-- or use PATCH -->

                            <input type="hidden" name="id" value="{{ $barang->id }}">
                            <input type="hidden" name="existing_foto" value="{{ $barang->foto }}"> <!-- Menyimpan nama foto yang ada -->

                            <div class="row mb-3">
                                <label for="kode_barang" class="col-sm-2 col-form-label">Kode Barang <span class="text-danger">*</span></label>
                                <div class="form-group col-sm-10">
                                    <input name="kode_barang" value="{{ $barang->kode }}" class="form-control" type="text" id="kode_barang" required>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="nama" class="col-sm-2 col-form-label">Nama Barang <span class="text-danger">*</span></label>
                                <div class="form-group col-sm-10">
                                    <input name="nama" value="{{ $barang->nama }}" class="form-control" type="text" id="nama" required>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="kelompok_barang" class="col-sm-2 col-form-label">Kelompok Barang <span class="text-danger">*</span></label>
                                <div class="col-sm-10">
                                    <select name="kelompok_id" class="form-select" required>
                                        <option selected="" disabled>Pilih jenis kelompok barang</option>
                                        @foreach($kelompok as $kel)
                                        <option value="{{$kel->id}}" {{ $kel->id == $barang->kelompok_id ? 'selected' : '' }}>{{$kel->nama}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="qty_item" class="col-sm-2 col-form-label">Stok Barang</label>
                                <div class="form-group col-sm-10">
                                    <input name="qty_item" value="{{ $barang->qty_item }}" class="form-control" type="text" id="qty_item">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="satuan" class="col-sm-2 col-form-label">Satuan Barang <span class="text-danger">*</span></label>
                                <div class="form-group col-sm-10">
                                    <select name="satuan" id="satuan" class="form-select" required>
                                        <option value="" disabled>Pilih satuan barang</option>
                                        @foreach($satuans as $satuan_item)
                                            <option value="{{ $satuan_item }}" {{ $satuan_item == $barang->satuan ? 'selected' : '' }}>{{ $satuan_item }}</option>
                                        @endforeach
                                        <option value="lainnya" {{ $barang->satuan == 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                                    </select>
                                </div>
                                <div class="form-group col-sm-7" id="satuanBaruContainer" style="{{ $barang->satuan == 'lainnya' ? 'display: block;' : 'display: none;' }}">
                                    <div class="d-flex">
                                        <label for="satuanBaru" class="col-form-label me-2" style="width: 40%;">Masukkan Satuan Baru</label>
                                        <input name="satuanBaru" class="form-control" type="text" id="satuanBaru" value="{{ old('satuanBaru', $barang->satuan == 'lainnya' ? $barang->satuan : '') }}">
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="foto" class="col-sm-2 col-form-label">Foto Barang</label>
                                <div class="form-group col-sm-10">
                                    @if($barang->foto_barang)
                                        <p class="text-success">Foto saat ini:</p>
                                        <div class="mt-2">
                                            <!-- Menggunakan path lengkap dari barang->foto -->
                                            <img src="{{ asset($barang->foto_barang) }}" alt="Foto Barang" class="img-fluid" style="max-width: 200px;">
                                        </div>
                                    @else
                                        <p class="text-warning">Belum ada foto</p>
                                    @endif

                                    <input name="foto" class="form-control mt-2" type="file" id="foto" accept=".jpg,.jpeg,.png">
                                    <small class="form-text text-muted">Usahakan gambar dalam bentuk PNG atau JPG untuk hasil yang lebih baik.</small>
                                </div>
                            </div>

                            <input type="submit" class="btn btn-info waves-effect waves-light" value="Edit Barang">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const satuanSelect = document.getElementById('satuan');
        const satuanBaruContainer = document.getElementById('satuanBaruContainer');
        const satuanBaruInput = document.getElementById('satuanBaru');
        const form = document.getElementById('myForm');

        satuanSelect.addEventListener('change', function() {
            if (this.value === 'lainnya') {
                satuanBaruContainer.style.display = 'block'; // Show the input and label
                satuanBaruInput.disabled = false; // Enable input field
            } else {
                satuanBaruContainer.style.display = 'none'; // Hide the input and label
                satuanBaruInput.disabled = true;  // Disable input field
                satuanBaruInput.value = '';       // Clear input field
            }
        });

        form.addEventListener('submit', function() {
            if (satuanSelect.value === 'lainnya') {
                const satuanBaruValue = satuanBaruInput.value;
                const hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';
                hiddenInput.name = 'satuanBaru';
                hiddenInput.value = satuanBaruValue;
                form.appendChild(hiddenInput);
            }
        });
    });
</script>

<script type="text/javascript">
    $(document).ready(function (){
        $('#myForm').validate({
            rules: {
                nama: {
                    required : true,
                },
                kelompok_id: {
                    required: true,
                },
                kode_barang: {
                    required: true,
                },
                satuan: {
                    required: true,
                }
            },
            messages: {
                nama: {
                    required: "Nama barang harus diisi.",
                },
                kelompok_id: {
                    required: "Kelompok barang harus dipilih.",
                },
                kode_barang: {
                    required: "Kode barang harus diisi.",
                },
                satuan: {
                    required: "Satuan barang harus dipilih.",
                }
            },
            errorElement : 'span', 
            errorPlacement: function (error, element) {
                error.addClass('invalid-feedback');
                element.closest('.form-group').append(error);
            },
            highlight : function(element, errorClass, validClass) {
                $(element).addClass('is-invalid');
            },
            unhighlight : function(element, errorClass, validClass) {
                $(element).removeClass('is-invalid');
            },
        });
    });
</script>
@endsection
