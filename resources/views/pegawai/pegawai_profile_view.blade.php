@extends(auth()->user()->role === 'admin' ? 'admin.admin_master' : 
         (auth()->user()->role === 'supervisor' ? 'supervisor.supervisor_master' : 
         'pegawai.pegawai_master'))

@section(auth()->user()->role === 'admin' ? 'admin' : 
         (auth()->user()->role === 'supervisor' ? 'supervisor' : 'pegawai'))

<div class="page-content">
<div class="container-fluid">


<div class="row">
    <div class="col-lg-6">
        <div class="card"><br><br>
<center>
    <img class="rounded-circle header-profile-user" style="object-fit: cover; object-position: top center;" src="{{ !empty($adminData->foto) ? url($adminData->foto) : url('upload/no_image.jpg') }}"
    alt="Header Avatar">
</center>

            <div class="card-body">
                <h4 class="card-title">Name : {{ $adminData->name }} </h4>
                <hr>
                <h4 class="card-title">User Email : {{ $adminData->email }} </h4>
                <hr>
                <h4 class="card-title">User Name : {{ $adminData->username }} </h4>
                <hr>
                <a href="{{ route('edit.profile') }}" class="btn btn-info btn-rounded waves-effect waves-light" > Edit Profile</a>
                
            </div>
        </div>
    </div> 
                            
        
                        </div> 


</div>
</div>

@endsection 
