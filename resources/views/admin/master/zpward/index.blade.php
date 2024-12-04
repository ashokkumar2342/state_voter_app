@extends('admin.layout.base')
@section('body')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h3>Zila Parishad Ward</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right"> 
                </ol>
            </div>
        </div> 
        <div class="card card-info"> 
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-4">
                        <div class="card card-primary"> 
                            <form action="{{ route('admin.Master.DistrictsZpWardStore') }}" method="post" class="add_form" no-reset="true" select-triger="district_select_box">
                                {{ csrf_field() }}
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">States</label>
                                        <span class="fa fa-asterisk"></span>
                                        <select name="states" class="form-control"  onchange="callAjax(this,'{{ route('admin.Master.stateWiseDistrict') }}','district_select_box');callAjax(this,'{{ route('admin.Master.ZilaParishadTable') }}'+'?district_id='+$('#district_select_box').val(),'zp_ward_table')">
                                            <option selected disabled>Select States</option>
                                            @foreach ($States as $State)
                                            <option value="{{ $State->id }}">{{ $State->code }}--{{ $State->name_e }}</option>  
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">District</label>
                                        <span class="fa fa-asterisk"></span>
                                        <select name="district_id" class="form-control" id="district_select_box" data-table="zp_ward_datatable" onchange="callAjax(this,'{{ route('admin.Master.ZilaParishadTable') }}'+'?district_id='+$('#district_select_box').val(),'zp_ward_table')">
                                            <option selected disabled>Select District</option>
                                        </select>
                                    </div>  
                                    <div class="form-group">
                                        <label for="exampleInputPassword1">How Many Wards To Be Created</label>
                                        <span class="fa fa-asterisk"></span>
                                        <input type="text" name="zp_ward" class="form-control"maxlength="2" onkeypress='return event.charCode >= 48 && event.charCode <= 57' required>
                                    </div> 
                                </div> 
                                <div class="card-footer text-center">
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </div>
                            </form>
                        </div> 
                    </div>
                    <div class="col-lg-8">
                        <div class="card card-primary" id="zp_ward_table"> 
                             <table id="district_datatable" class="table table-striped table-hover control-label">
                                 <thead>
                                     <tr>
                                      <th>Ward No.</th>
                                      <th>Ward Name(English)</th>
                                      <th>Ward Name(Hindi)</th>
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

