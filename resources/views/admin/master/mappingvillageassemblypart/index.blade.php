@extends('admin.layout.base')
@section('body')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h3>Mapping (Panchayat / MC's - Assembly Part)</h3>
            </div>
        </div> 
    </div>
    <div class="card card-info">
        <div class="card-body">
            <form action="{{ route('admin.Master.MappingVillageAssemblyPartStore') }}" method="post" class="add_form" content-refresh="district_table" no-reset="true" select-triger="village_select_box,assembly_select_box">
            {{ csrf_field() }}
                <div class="row">
                    <div class="col-lg-4 form-group">
                        <label for="exampleInputEmail1">District</label>
                        <span class="fa fa-asterisk"></span>
                        <select name="district" class="form-control select2" select2="true" id="district_select_box" onchange="callAjax(this,'{{ route('admin.Master.DistrictWiseBlock') }}','block_select_box');callAjax(this,'{{ route('admin.Master.MappingVillageAssemblyPartFilter') }}'+'?district_id='+$('#district_select_box').val(),'value_div_id')" required>
                            <option selected disabled>Select District</option>
                            @foreach ($rs_district as $rs_val)
                                <option value="{{ Crypt::encrypt($rs_val->opt_id) }}">{{ $rs_val->opt_text }}</option>  
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-4 form-group">
                        <label for="exampleInputEmail1">Block / MC's</label>
                        <span class="fa fa-asterisk"></span>
                        <select name="block_mcs" class="form-control select2" id="block_select_box" onchange="callAjax(this,'{{ route('admin.Master.BlockWiseVillage') }}'+'?id='+this.value+'&district_id='+$('#district_select_box').val(),'village_select_box')" required>
                            <option selected disabled>Select Block / MC's</option> 
                        </select>
                    </div>
                    <div class="col-lg-4 form-group">
                        <label for="exampleInputEmail1">Panchayat / MC's</label>
                        <span class="fa fa-asterisk"></span>
                        <select name="village" class="form-control select2" id="village_select_box" select2="true" data-table-new-without-pagination="ajax_data_table" onchange="callAjax(this,'{{ route('admin.Master.MappingVillageAssemblyPartTable') }}'+'?district_id='+$('#district_select_box').val(),'result_div_id')" required>
                            <option selected disabled>Select Panchayat / MC's</option>
                        </select>
                    </div>
                </div>
                <div class="row" id="value_div_id">
                                      
                </div>
            </form> 
        </div>
    </div>
    <div class="card card-info">
        <div class="card-body">
            <div class="row" id="result_div_id"> 

            </div>    
        </div>
    </div> 
</section>
@endsection
@push('scripts')


