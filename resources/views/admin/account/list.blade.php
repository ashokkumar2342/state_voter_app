@extends('admin.layout.base')
@section('body')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h3>List Users</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right"> 
                </ol>
            </div>
        </div> 
        <div class="card card-info"> 
            <div class="card-body">
               
              <div class="col-lg-12">
              <div class="table-responsive">
              <table id="dataTable" class="table table-bordered table-striped table-hover">
                <thead>
                <tr>
                  <th>Sr.No.</th> 
                  <th>Name</th>
                  <th>Mobile</th> 
                  <th>Email Id</th>
                  <th>Role</th> 
                  <th>Status</th>                  
                  <th>Action</th>
                </tr>
                </thead>
                <tbody>
                  @php
                  $arrayId=1;
                     
                  @endphp
                @foreach($accounts as $account)
                <tr>
                  <td>{{ $arrayId ++ }}</td>
                  <td>{{ $account->first_name }} {{ $account->last_name}}</td>
                  <td>{{ $account->mobile }}</td> 
                  <td>{{ $account->email }}</td>
                  <td>{{ $account->name or '' }}</td>
                  
                  <td>
                    <a href="{{ route('admin.account.status',Crypt::encrypt($account->id)) }}" data-parent="tr" class="label {{ ($account->status == 1) ?'btn-success':'btn-danger'}} btn btn-xs">{{ ($account->status == 1)? 'Active' : 'Inactive' }}</a>
                  </td>  
                  
                  <td> 
                    <a href="#" onclick="callPopupLarge(this,'{{ route('admin.account.edit',Crypt::encrypt($account->id)) }}')" class="btn btn-info btn-xs"><i class="fa fa-pencil"></i></a>
                    <a  href="{{ route('admin.account.delete',Crypt::encrypt($account->id)) }}" onclick="return confirm('Are you sure to delete this data ?')"  class="btn btn-danger btn-xs"><i class="fa fa-trash"></i></a>
                   
                  
                  </td>
                </tr> 
                @endforeach
                </tbody>
              </table>
            </div>
            </div>
            </div>
            
            </div> 
        </div>
    </div>     
    </section>
    @endsection
@push('links')
<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.15/css/jquery.dataTables.min.css">
@endpush
@push('scripts')
 <script type="text/javascript" src="//cdn.datatables.net/1.10.15/js/jquery.dataTables.min.js"></script>
 <script type="text/javascript">
     $(document).ready(function(){
        $('#dataTable').DataTable();
    });
     
 
 
</script>
   
@endpush