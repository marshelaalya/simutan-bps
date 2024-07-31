@extends('admin.admin_master')
@section('admin')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<div class="page-content">
    <div class="container-fluid">

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">

                        <h4 class="card-title">Halaman Pengajuan Barang</h4><br><br>
                        
                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                        <label for="date" class="form-label">Tanggal</label>
                                        <input class="form-control" name="date" type="date" id="date">
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
                                            <option selected disabled>Pilih Barang yang Akan Diajukan</option>
                                            @foreach($barang as $bar)
                                                <option value="{{ $bar->id }}">{{ $bar->nama }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="req_qty" class="form-label">Kuantitas Permintaan</label>
                                        <input class="form-control" name="req_qty" type="text" id="req_qty">
                                    </div>
                                </div>

                                <div class="mt-3">
                                    <label class="mb-1">Deskripsi</label>
                                    {{-- <p class="text-muted mb-3 font-14">
                                        Penjelasan.
                                    </p> --}}
                                    <textarea id="textarea" class="form-control" maxlength="225" rows="3" placeholder="Penjelasan. (Maksimal 225 Karakter)"></textarea>
                                </div>

                                <div class="col-md-4">
                                    <div class="md-3">
                                        <label for="example-text-input" class="form-label" style="margin-top: 40px;"></label>
                                        <input type="submit" class="btn btn-primary waves-effect waves-light" value="Tambah Lagi">                    
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
    $(function(){
        $(document).on('change','#kelompok_id', function(){
            var kelompok_id = $(this).val();
            $.ajax({
                url:"{{ route('get-category') }}",
                type: "GET",
                data:{kelompok_id:kelompok_id},
                success:function(data){
                    var html='<option value="">Pilih barang yang ingin diajukan</option>';
                    $.each(data,function(key,v){
                        html += '<option value=" ' +v.barang_id+ ' "> ' + v.barang.nama + ' </option>'
                    });
                    $('barang_id').html(html);
                }
            })
        });
    });

</script>

@endsection
