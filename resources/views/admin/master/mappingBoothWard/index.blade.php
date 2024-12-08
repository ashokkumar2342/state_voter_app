@extends('admin.layout.base')
@section('body')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h3>Mapping (Polling Booth-Panchayat/MC's Ward)</h3>
            </div>
        </div> 
    </div>
    <div class="card card-info">
        <div class="card-body">
            <form action="{{ route('admin.Master.MappingBoothWardStore') }}" method="post" class="add_form" no-reset="true">
            {{ csrf_field() }}
                <div class="row">
                    <div class="col-lg-3 form-group">
                        <label for="exampleInputEmail1">District</label>
                        <span class="fa fa-asterisk"></span>
                        <select name="district" class="form-control select2" id="district_select_box" onchange="callAjax(this,'{{ route('admin.Master.DistrictWiseBlock') }}','block_select_box')">
                            <option selected disabled>Select District</option>
                            @foreach ($rs_district as $rs_val)
                                <option value="{{ Crypt::encrypt($rs_val->opt_id) }}">{{ $rs_val->opt_text }}</option>  
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-3 form-group">
                        <label for="exampleInputEmail1">Block / MC's</label>
                        <span class="fa fa-asterisk"></span>
                        <select name="block" class="form-control select2" id="block_select_box" onchange="callAjax(this,'{{ route('admin.Master.BlockWiseVillage') }}'+'?id='+this.value+'&district_id='+$('#district_select_box').val(),'village_select_box')">
                            <option selected disabled>Select Block / MC's</option> 
                        </select>
                    </div>
                    <div class="col-lg-3 form-group">
                        <label for="exampleInputEmail1">Panchayat / MC's</label>
                        <span class="fa fa-asterisk"></span>
                        <select name="village" class="form-control select2" id="village_select_box" select2="true" data-table="ward_datatable" onchange="callAjax(this,'{{ route('admin.Master.MappingVillageWiseBooth') }}'+'?village_id='+$('#village_select_box').val(),'booth_select_box')">
                            <option selected disabled>Select Panchayat / MC's</option>

                        </select>
                    </div>
                    <div class="col-lg-3 form-group">
                        <label for="exampleInputEmail1">Booth</label>
                        <span class="fa fa-asterisk"></span>
                        <select name="booth" class="form-control select2" id="booth_select_box" duallistbox="true" onchange="callAjax(this,'{{ route('admin.Master.MappingVillageOrBoothWiseWard') }}'+'?village_id='+$('#village_select_box').val(),'ward_select_box')">
                            <option selected disabled>Select Booth</option>

                        </select>
                    </div>
                    <div class="col-lg-12" id="ward_select_box">
                        
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


