@extends('admin.layout.base')
@section('body')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h3>Add Districts</h3>
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
                            <form action="{{ route('admin.Master.districtsStore') }}" method="post" class="add_form"  no-reset="true" reset-input-text="code,name_english,zp_ward,name_local_language" select-triger="state_select_box">
                                {{ csrf_field() }}
                                <div class="card-body row">
                                    <div class="col-lg-6 form-group">
                                    <label for="exampleInputEmail1">States</label>
                                    <span class="fa fa-asterisk"></span>
                                    <select name="states" class="form-control" id="state_select_box" data-table="district_datatable" onchange="callAjax(this,'{{ route('admin.Master.DistrictsTable') }}','district_table')">    
                                        <option selected disabled>Select State</option>                                      
                                        @foreach ($States as $State)
                                        <option value="{{ $State->id }}">{{ $State->code }}--{{ $State->name_e }}</option>  
                                        @endforeach
                                    </select>
                                    </div>
                                    <div class="col-lg-6 form-group">
                                        <label for="exampleInputEmail1">District Code</label>
                                        <span class="fa fa-asterisk"></span>
                                        <input type="text" name="code" id="code" class="form-control" placeholder="Enter Code"maxlength="5" required>
                                    </div>
                                    <div class="col-lg-4 form-group">
                                        <label for="exampleInputPassword1">District Name (English)</label>
                                        <span class="fa fa-asterisk"></span>
                                        <input type="text" name="name_english" id="name_english" class="form-control" placeholder="Enter Name (English)" maxlength="50" required>
                                    </div>
                                    <div class="col-lg-4 form-group">
                                        <label for="exampleInputPassword1">District Name (Hindi)</label>
                                        <span class="fa fa-asterisk"></span>
                                        <input type="text" name="name_local_language" id="name_local_language" class="form-control" placeholder="Enter Name (In hindi)" maxlength="50" required>
                                    </div>
                                    <div class="col-lg-4 form-group">
                                        <label for="exampleInputPassword1">Zila Parishad Wards To Be Created</label>
                                        <span class="fa fa-asterisk"></span>
                                        <input type="text" name="zp_ward" id="zp_ward" class="form-control"maxlength="2" required onkeypress='return event.charCode >= 48 && event.charCode <= 57'>
                                    </div>
                                    
                                </div> 
                                <div class="card-footer text-center">
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </div>
                            </form>
                        </div> 
                    </div>
                    <div class="col-lg-12">
                        <div class="card card-primary" id="district_table"> 
                             <table id="district_datatable" class="table table-striped table-hover control-label">
                                 <thead>
                                     <tr>
                                         <th>Code</th>
                                         <th>Name (English)</th>
                                         <th>Name (Hindi)</th>
                                         <th>Total Z.P. Ward</th>
                                         <th>Action</th>
                                          
                                     </tr>
                                 </thead>
                                 <tbody>
                                    
                                 </tbody>
                             </table>
                        </div> 
                        
                    </div> 
                </div>
            </div> 
        </div> 
    </section>
    @endsection
    @push('scripts')
    <script type="text/javascript"> 
        $('#district_datatable').DataTable();
    </script>
    @endpush 

