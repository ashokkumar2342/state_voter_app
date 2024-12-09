@extends('admin.layout.base')
@section('body')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h3>Shift Voters (In Booth :: Draft Sr. No.)</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right"> 
                </ol>
            </div>
        </div> 
        <div class="card card-info"> 
            <div class="card-body">
                <form action="{{ route('admin.Master.change.voter.ward.with.booth.store') }}" method="post" no-reset="true" class="add_form" reset-input-text="from_sr_no,to_sr_no" @if ($refreshdata == 1) select-triger="from_booth_select_box" @endif>
                    {{ csrf_field() }}
                    <div class="row"> 
                        <div class="col-lg-4 form-group">
                            <label for="exampleInputEmail1">District</label>
                            <span class="fa fa-asterisk"></span>
                            <select name="district" class="form-control select2" id="district_select_box" onchange="callAjax(this,'{{ route('admin.Master.DistrictWiseBlock') }}','block_select_box')">
                                <option selected disabled>Select District</option>
                                @foreach ($rs_district as $rs_val)
                                    <option value="{{ Crypt::encrypt($rs_val->opt_id) }}">{{ $rs_val->opt_text }}</option>  
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-4 form-group">
                            <label for="exampleInputEmail1">Block / MC's</label>
                            <span class="fa fa-asterisk"></span>
                            <select name="block" class="form-control select2" id="block_select_box" onchange="callAjax(this,'{{ route('admin.Master.BlockWiseVillage') }}'+'?id='+this.value+'&district_id='+$('#district_select_box').val(),'village_select_box')">
                                <option selected disabled>Select Block / MC's</option> 
                            </select>
                        </div>
                        <div class="col-lg-4 form-group">
                            <label for="exampleInputEmail1">Panchayat / MC's</label>
                            <span class="fa fa-asterisk"></span>
                            <select name="village" class="form-control select2" id="village_select_box" select2="true" onchange="callAjax(this,'{{ route('admin.Master.change.voter.village.wise.ward.booth') }}','value_div_id')">
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
                <div class="row" id="result_table">
                </div>
            </div>
        </div>
    </div> 
</section>
@endsection

