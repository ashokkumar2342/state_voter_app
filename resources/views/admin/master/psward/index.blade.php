@extends('admin.layout.base')
@section('body')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h3>Panchayat Samiti Wards</h3>
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
                            <form action="{{ route('admin.Master.BlockMCSpsWardStore') }}" method="post" select-triger="block_select_box" no-reset="true" no-reset="true" class="add_form">
                                {{ csrf_field() }}
                                <div class="row"> 
                                    <div class="col-lg-3 form-group">
                                    <label for="exampleInputEmail1">State</label>
                                    <span class="fa fa-asterisk"></span>
                                    <select name="states" id="state_id" class="form-control select2" onchange="callAjax(this,'{{ route('admin.Master.stateWiseDistrict') }}','district_select_box')" required>
                                    <option selected disabled>Select State</option>
                                    @foreach ($States as $State)
                                    <option value="{{ $State->id }}">{{ $State->code }}--{{ $State->name_e }}</option>  
                                    @endforeach
                                    </select>
                                    </div>
                                    <div class="col-lg-3 form-group">
                                    <label for="exampleInputEmail1">District</label>
                                    <span class="fa fa-asterisk"></span>
                                    <select name="district" class="form-control select2" id="district_select_box" onchange="callAjax(this,'{{ route('admin.Master.DistrictWiseBlock') }}','block_select_box')" required>
                                    <option selected disabled>Select District</option>
                                    </select>
                                    </div>
                                    <div class="col-lg-3 form-group">
                                    <label for="exampleInputEmail1">Block / MC's</label>
                                    <span class="fa fa-asterisk"></span>
                                    <select name="block_id" class="form-control select2" id="block_select_box" data-table="ps_ward_datatable" onchange="callAjax(this,'{{ route('admin.Master.PanchayatSamitiTable') }}','ps_ward_table')" required>
                                        <option selected disabled>Select Block / MC's</option> 
                                    </select>
                                    </div>
                                    <div class="col-lg-3 form-group">
                                        <label for="exampleInputPassword1">Panchyat Samiti Wards To Be Created</label>
                                        <span class="fa fa-asterisk"></span>
                                         
                                        <input type="text" name="ps_ward" class="form-control"maxlength="2" rquired onkeypress='return event.charCode >= 48 && event.charCode <= 57'>
                                    </div>
                                    <div class="col-lg-12 form-group" style="margin-top: 30px">
                                         <input type="submit" class="form-control btn btn-success">
                                    </div>
                                </div> 
                            </form>
                         
                    </div>
                    <div class="col-lg-12">
                        <div class="card card-primary table-responsive" id="ps_ward_table"> 
                             <table id="district_datatable" class="table table-striped table-hover control-label">
                                 <thead>
                                     <tr>
                                      <th>Ward No.</th>
                                      <th>Ward Name (English)</th>
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
<script type="text/javascript">
    function disablewardNo() {
    document.getElementById("ward_select_box").disabled = false;
}

</script>
   