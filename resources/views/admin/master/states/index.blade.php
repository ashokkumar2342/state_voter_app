@extends('admin.layout.base')
@section('body')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h3>Add States</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right"> 
                </ol>
            </div>
        </div> 
        <div class="card card-info"> 
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card card-primary"> 
                            <form action="{{ route('admin.Master.store') }}" method="post" class="add_form" content-refresh="state_table">
                                {{ csrf_field() }}
                                <div class="card-body row">
                                    <div class="form-group col-lg-4">
                                        <label for="exampleInputEmail1">States Code</label>
                                        <span class="fa fa-asterisk"></span>
                                        <input type="text" name="code" class="form-control" placeholder="Enter Code" maxlength="5" required>
                                    </div>
                                    <div class="form-group col-lg-4">
                                        <label for="exampleInputPassword1">State Name (English)</label>
                                        <span class="fa fa-asterisk"></span>
                                        <input type="text" name="name_english" class="form-control" placeholder="Enter Name (English)" maxlength="50" required>
                                    </div>
                                    <div class="form-group col-lg-4">
                                        <label for="exampleInputPassword1">State Name (Hindi)</label>
                                        <span class="fa fa-asterisk"></span>
                                        <input type="text" name="name_local_language" class="form-control" placeholder="Enter Name (In Hindi)" maxlength="50" required>
                                    </div>
                                     
                                </div> 
                                <div class="card-footer text-center">
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </div>
                            </form>
                        </div> 
                    </div>
                    <div class="col-lg-12">
                        <div class="card card-primary table-responsive"> 
                             <table id="state_table" class="table table-striped table-bordered">
                                 <thead>
                                     <tr>
                                         <th>Code</th>
                                         <th class="text-nowrap">Name (English)</th>
                                         <th class="text-nowrap">Name (Hindi)</th>
                                         <th>Action</th>
                                          
                                     </tr>
                                 </thead>
                                 <tbody>
                                    @foreach ($States as $State)
                                     <tr>
                                         <td>{{ $State->code }}</td>
                                         <td>{{ $State->name_e }}</td>
                                         <td>{{ $State->name_l }}</td>
                                         <td class="text-nowrap">
                                             <a onclick="callPopupLarge(this,'{{ route('admin.Master.edit',$State->id) }}')" title="" class="btn btn-info btn-xs"><i class="fa fa-edit"></i></a>
                                             <a href="{{ route('admin.Master.delete',Crypt::encrypt($State->id)) }}" onclick="return confirm('Are you sure you want to delete this item?');"  title="" class="btn btn-danger btn-xs"><i class="fa fa-trash"></i></a>
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
    </section>
    @endsection
    @push('links')
<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.15/css/jquery.dataTables.min.css">
@endpush
@push('scripts')
 <script type="text/javascript" src="//cdn.datatables.net/1.10.15/js/jquery.dataTables.min.js"></script>
 <script type="text/javascript">
     $(document).ready(function(){
        $('#state_table').DataTable();
    });
</script> 
@endpush
