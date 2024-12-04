@extends('admin.layout.base')
@section('body')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h3>Panchayat / MC's Wards</h3>
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
                            <form action="{{ route('admin.Master.ward.store') }}" method="post" class="add_form" no-reset="true" select-triger="village_select_box">
                                {{ csrf_field() }}
                                <div class="card-body">
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
                                    <select name="block" class="form-control select2" id="block_select_box" onchange="callAjax(this,'{{ route('admin.Master.BlockWiseVillage') }}'+'?id='+this.value+'&district_id='+$('#district_select_box').val(),'village_select_box')" required>
                                        <option selected disabled>Select Block / MC's</option> 
                                    </select>
                                    </div>
                                    <div class="col-lg-3 form-group">
                                        <label for="exampleInputEmail1">Panchayat / MC's</label>
                                        <span class="fa fa-asterisk"></span>
                                        <select name="village" class="form-control select2" id="village_select_box" select2="true" data-table="ward_datatable" onchange="callAjax(this,'{{ route('admin.Master.ward.table') }}','ward_table')" required>
                                            <option selected disabled>Select Panchayat / MC's</option>
                                            
                                        </select>
                                    </div>
                                    <div class="col-lg-6 form-group">
                                        <label for="exampleInputEmail1">Wards To Be Created</label>
                                        <span class="fa fa-asterisk"></span>
                                        <input type="text" name="ward" class="form-control" placeholder="" maxlength="2" required onkeypress='return event.charCode >= 48 && event.charCode <= 57'>
                                    </div>
                                    <div class="col-lg-6 form-group">
                                     <input type="submit" class="form-control btn btn-primary" value="Save" style="margin-top: 30px">
                                    </div>
                            </form>
                        </div> 
                    </div>
                    <div class="col-lg-12" id="ward_table">
                        <div class="card card-primary table-responsive"> 
                             <table id="district_table" class="table table-striped table-hover control-label">
                                 <thead>
                                     <tr>
                                         <th class="text-nowrap">Ward No.</th>
                                         <th class="text-nowrap">Ward Name (In English)</th>
                                         <th class="text-nowrap">Ward Name (In Hindi)</th> 
                                         <th class="text-nowrap">Action</th> 
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
        $('#ddd').DataTable();
    </script> 

