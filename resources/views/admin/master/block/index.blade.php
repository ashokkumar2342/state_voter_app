@extends('admin.layout.base')
@section('body')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h3>Block / MC's</h3>
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
                            <form action="{{ route('admin.Master.BlockMCSStore') }}" method="post" class="add_form" no-reset="true" select-triger="district_select_box" reset-input-text="code,name_english,name_local_language,block_mc_type,ps_ward">
                                {{ csrf_field() }} 
                                    <div class="row"> 
                                    <div class="col-lg-6 form-group">
                                        <label for="exampleInputEmail1">State</label>
                                        <span class="fa fa-asterisk"></span>
                                        <select name="states" class="form-control"  onchange="callAjax(this,'{{ route('admin.Master.stateWiseDistrict') }}','district_select_box');callAjax(this,'{{ route('admin.Master.BlockMCSTable') }}'+'?district_id='+$('#district_select_box').val(),'block_table')">
                                            <option selected disabled>Select States</option>
                                            @foreach ($States as $State)
                                            <option value="{{ $State->id }}">{{ $State->code }}--{{ $State->name_e }}</option>  
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-lg-6 form-group">
                                        <label for="exampleInputEmail1">District</label>
                                        <span class="fa fa-asterisk"></span>
                                        <select name="district" class="form-control" id="district_select_box" data-table="block_datatable" onchange="callAjax(this,'{{ route('admin.Master.BlockMCSTable') }}'+'?district_id='+$('#district_select_box').val(),'block_table')">
                                            <option selected disabled>Select District</option>
                                        </select>
                                    </div>
                                    <div class="col-lg-3 form-group">
                                        <label for="exampleInputEmail1">Block Code</label>
                                        <span class="fa fa-asterisk"></span>
                                        <input type="text" name="code" id="code" class="form-control" placeholder="Enter Code" maxlength="5" required>
                                    </div>
                                    <div class="col-lg-3 form-group">
                                        <label for="exampleInputPassword1">Block Name(English)</label>
                                        <span class="fa fa-asterisk"></span>
                                        <input type="text" name="name_english" id="name_english" class="form-control" placeholder="Enter Name (English)" maxlength="50" required>
                                    </div>
                                    <div class="col-lg-3 form-group">
                                        <label for="exampleInputPassword1">Block Name(Hindi)</label>
                                        <span class="fa fa-asterisk"></span>
                                        <input type="text" name="name_local_language" id="name_local_language" class="form-control" placeholder="Enter Name (In Hindi)" maxlength="100" required>
                                    </div>
                                    <div class="col-lg-3 form-group">
                                        <label for="exampleInputPassword1">Block / MC's Type</label>
                                        <span class="fa fa-asterisk"></span>
                                        <select name="block_mc_type_id" id="block_mc_type" class="form-control">
                                            <option selected disabled>Select Block / MC's Type</option>
                                            @foreach ($BlockMCTypes as $BlockMCType)
                                            <option value="{{ $BlockMCType->id }}">{{ $BlockMCType->block_mc_type_e }}</option>  
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-lg-4 form-group">
                                        <label for="exampleInputPassword1">Stamp Line 1</label>
                                         
                                        <input type="text" name="stamp_l1" id="stamp_l1" class="form-control" maxlength="100">
                                    </div>
                                    <div class="col-lg-4 form-group">
                                        <label for="exampleInputPassword1">Stamp Line 2</label>
                                         
                                        <input type="text" name="stamp_l2" id="stamp_l2" class="form-control" maxlength="100">
                                    </div>
                                    <div class="col-lg-4 form-group"> 
                                        <label for="exampleInputPassword1">Panchyat Samiti Ward To Be Created</label>
                                        <span class="fa fa-asterisk"></span>
                                         
                                        <input type="text" name="ps_ward" id="ps_ward" class="form-control" maxlength="2" required onkeypress='return event.charCode >= 48 && event.charCode <= 57'>
                                    </div>
                                    
                                </div> 
                                <div class="card-footer text-center">
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </div>
                            </form> 
                    </div>
                    <div class="col-lg-12">
                        <div class="card card-primary table-responsive" id="block_table"> 
                             <table id="district_table" class="table table-striped table-hover control-label">
                                 <thead>
                                     <tr>
                                         <th>Code</th>
                                         <th>Name (English)</th>
                                         <th>Name (Hindi)</th>
                                         <th>Block / MC's Type</th>
                                         <th>Total P.S. Ward</th>
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
    </section>
    @endsection
    @push('scripts')
    <script type="text/javascript">
        $('#btn_click_by_form').click();
        $('#district_table').DataTable();
    </script>
    @endpush 

