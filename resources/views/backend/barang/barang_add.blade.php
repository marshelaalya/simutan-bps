@extends(auth()->user()->role === 'admin' ? 'admin.admin_master' : (auth()->user()->role === 'supervisor' ? 'supervisor.supervisor_master' : 'pegawai.pegawai_master'))
@section(auth()->user()->role === 'admin' ? 'admin' : (auth()->user()->role === 'supervisor' ? 'supervisor' : 'pegawai'))
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<div class="page-content">
    <div class="container-fluid">
        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0 text-info">Penambahan Barang</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Barang</a></li>
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Persediaan Barang</a></li>
                            <li class="breadcrumb-item active">Tambah Barang</li>
                        </ol>
                    </div>

                </div>
            </div>
        </div>
        <!-- end page title -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">

                        <h4 class="card-title">Tambah Barang</h4><br>

                        <form method="post" action="{{ route('barang.store') }}" id="myForm">
                            @csrf

                            <div class="row mb-3">
                                <label for="kode_barang" class="col-sm-2 col-form-label">Kode Barang</label>
                                <div class="form-group col-sm-10">
                                    <input name="kode_barang" class="form-control" type="text" id="kode_barang">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="nama" class="col-sm-2 col-form-label">Nama Barang</label>
                                <div class="form-group col-sm-10">
                                    <input name="nama" class="form-control" type="text" id="nama">
                                </div>
                            </div>
                            <!-- end row -->

                            <div class="row mb-3">
                                <label for="kelompok_barang" class="col-sm-2 col-form-label">Kelompok Barang</label>
                                <div class="col-sm-10">
                                    <select name="kelompok_id" class="form-select" aria-label="Default select example">
                                        <option selected="" disabled>Pilih jenis kelompok barang</option>
                                        @foreach($kelompok as $kel)
                                        <option value="{{$kel->id}}">{{$kel->nama}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <!-- end row -->

                            <div class="row mb-3">
                                <label for="qty_item" class="col-sm-2 col-form-label">Stok Barang</label>
                                <div class="form-group col-sm-10">
                                    <input name="qty_item" class="form-control" type="text" id="qty_item">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="satuan" class="col-sm-2 col-form-label">Satuan Barang</label>
                                <div class="form-group col-sm-10">
                                    <select name="satuan" id="satuan" class="form-select">
                                        <option selected disabled>Pilih satuan barang</option>
                                        @foreach($satuan as $satuan_item)
                                            <option value="{{ $satuan_item->satuan }}">{{ $satuan_item->satuan }}</option>
                                        @endforeach
                                        <option value="lainnya">Lainnya</option>
                                    </select>
                                </div>
                                <div class="form-group col-sm-7" id="satuanBaruContainer" style="display: none;">
                                    <div class="d-flex">
                                        <label for="satuanBaru" class="col-form-label me-2" style="width: 40%;">Masukkan Satuan Baru</label>
                                        <input name="satuanBaru" class="form-control" type="text" id="satuanBaru">
                                    </div>
                                </div>
                            </div>
                            

                            <div class="d-flex justify-content-end">
                                <input type="submit" class="btn btn-info waves-effect waves-light" value="Tambahkan Barang">
                            </div>

                        </form>
                    </div>
                </div>
            </div> <!-- end col -->
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const satuanSelect = document.getElementById('satuan');
        const satuanBaruContainer = document.getElementById('satuanBaruContainer');
    
        satuanSelect.addEventListener('change', function() {
            if (satuanSelect.value === 'lainnya') {
                // Mengubah lebar dropdown menjadi col-sm-2
                satuanSelect.parentElement.classList.remove('col-sm-10');
                satuanSelect.parentElement.classList.add('col-sm-3');
                // Menampilkan container untuk satuan baru
                satuanBaruContainer.style.display = 'block';
            } else {
                // Mengubah lebar dropdown menjadi col-sm-6
                satuanSelect.parentElement.classList.remove('col-sm-2');
                satuanSelect.parentElement.classList.add('col-sm-10');
                // Menyembunyikan container untuk satuan baru
                satuanBaruContainer.style.display = 'none';
            }
        });
    });
    </script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const satuanSelect = document.getElementById('satuan');
        const satuanBaruContainer = document.getElementById('satuanBaruContainer');
        const satuanBaruInput = document.getElementById('satuanBaru');

        satuanSelect.addEventListener('change', function() {
            if (this.value === 'lainnya') {
                satuanBaruContainer.style.display = 'block'; // Show the input and label
                satuanBaruInput.disabled = false; // Enable input field
                satuanBaru.style.marginLeft = '15px'; // Add margin-left when showing
            } else {
                satuanBaruContainer.style.display = 'none'; // Hide the input and label
                satuanBaruInput.disabled = true;  // Disable input field
                satuanBaruInput.value = '';       // Clear input field
                satuanBaruContainer.style.marginLeft = '0'; // Remove margin-left when hiding
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
                }
            },
            messages: {
                nama: {
                    required: "Nama barang harus diisi.",
                },
                kelompok_id: {
                    required: "Kelompok barang harus dipilih.",
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
