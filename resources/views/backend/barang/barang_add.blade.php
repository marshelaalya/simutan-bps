@extends('admin.admin_master')
@section('admin')
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
                                <label for="nama" class="col-sm-2 col-form-label">Stok Barang</label>
                                <div class="form-group col-sm-10">
                                    <input name="qty_item" class="form-control" type="text" id="nama">                    
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="nama" class="col-sm-2 col-form-label">Satuan Barang</label>
                                <div class="form-group col-sm-10">
                                    <input name="satuan" class="form-control" type="text" id="nama">                    
                                </div>
                            </div>
                            
                            <input type="submit" class="btn btn-info waves-effect waves-light" value="Tambahkan Barang">
                        </form>
                    </div>
                </div>
            </div> <!-- end col -->
        </div>
    </div>
</div>

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
