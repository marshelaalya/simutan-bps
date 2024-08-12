@extends('admin.admin_master')
@section('admin')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<div class="page-content">
    <div class="container-fluid">

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">

                        <h4 class="card-title">Ganti Role Pengguna</h4><br><br>
                        
                        <form method="post" action="{{ route('user.update', $user->id) }}" id="myForm">
                            @csrf

                            <input type="hidden" name="id" value="{{ $user->id }}">

                            <div class="row mb-3">
                                <label for="name" class="col-sm-2 col-form-label">Nama Pengguna</label>
                                <div class="form-group col-sm-10">
                                    <input name="name" value="{{ $user->name }}" class="form-control" type="text" id="name" readonly>
                                </div>
                            </div>
                        
                            <div class="row mb-3">
                                <label for="username" class="col-sm-2 col-form-label">Username</label>
                                <div class="form-group col-sm-10">
                                    <input name="username" value="{{ $user->username }}" class="form-control" type="text" id="username" readonly>
                                </div>
                            </div>
                        
                            <div class="row mb-3">
                                <label for="email" class="col-sm-2 col-form-label">Email</label>
                                <div class="form-group col-sm-10">
                                    <input name="email" value="{{ $user->email }}" class="form-control" type="email" id="email" readonly>
                                </div>
                            </div>
                        
                            <div class="row mb-3">
                                <label for="role" class="col-sm-2 col-form-label">Role</label>
                                <div class="col-sm-10">
                                    <select name="role" class="form-select" aria-label="Default select example" required>
                                        <option value="" disabled>Pilih role untuk User</option>
                                        <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                                        <option value="supervisor" {{ $user->role == 'supervisor' ? 'selected' : '' }}>Supervisor</option>
                                        <option value="pegawai" {{ $user->role == 'pegawai' ? 'selected' : '' }}>Pegawai</option>
                                    </select>
                                    
                                </div>
                            </div>
                            <div class="d-flex justify-content-end">
                                <input type="submit" class="btn btn-info waves-effect waves-light" value="Edit Pengguna">
                            </div>
                            
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
