@extends('admin.admin_master')
@section('admin')


 <div class="page-content">
                    <div class="container-fluid">

                        <!-- start page title -->
                        <div class="row">
                            <div class="col-12">
                                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                                    <h4 class="mb-sm-0">Permintaan All</h4>

                                     

                                </div>
                            </div>
                        </div>
                        <!-- end page title -->
                        
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

    <a href="" class="btn btn-dark btn-rounded waves-effect waves-light" style="float:right;">Tambah Permintaan</a> <br>

                    <h4 class="card-title">Permintaan All Data </h4>
                    

                    <table id="datatable" class="table table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead>
                        <tr>
                            <th>Tanggal</th> {{-- Tanggal Permintaan --}}
                            <th>Nama Pengaju</th>
                            <th>Catatan</th>
                            <th>Approval Admin</th>
                            <th>Approval Supervisor</th>
                            <th>Action</th>
                            
                        </thead>


                        <tbody>
                        	 
                        	@foreach($permintaans as $key => $item)
                        <tr>
                            <td> {{ $key+1}} </td>
                            <td> {{ $item->user_id }} </td> 
                            <td> {{ $item->tgl_request }} </td> 
                            <td> {{ $item->status }} </td> 
                            <!-- <td> {{ $item->ctt_admin }} </td> 
                            <td> {{ $item->ctt_spv }} </td>  -->
                            
                            <td>
   <a href=" " class="btn btn-info sm" title="Edit Data">  <i class="fas fa-edit"></i> </a>

     <a href=" " class="btn btn-danger sm" title="Delete Data" id="delete">  <i class="fas fa-trash-alt"></i> </a>

                            </td>
                           
                        </tr>
                        @endforeach
                        
                        </tbody>
                    </table>
        
                                    </div>
                                </div>
                            </div> <!-- end col -->
                        </div> <!-- end row -->
        
                     
                        
                    </div> <!-- container-fluid -->
                </div>
 

@endsection