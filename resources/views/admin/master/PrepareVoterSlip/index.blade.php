@extends('admin.layout.base')
@section('body')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h3>Prepare Voter Slip</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right"> 
                </ol>
            </div>
        </div> 
        <div class="card card-info"> 
            <div class="card-body">    
                <form action="{{ route('admin.prepare.voter.slip.generate') }}" method="post" no-reset="true" class="add_form">
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
                            <label for="exampleInputEmail1">Panchayat MC's</label>
                            <span class="fa fa-asterisk"></span>
                            <select name="village" class="form-control select2" id="village_select_box" multiselect-form="true" onchange="callAjax(this,'{{ route('admin.voter.VillageWiseWardMultiple') }}'+'?village_id='+this.value,'value_div_id')">
                                <option selected disabled>Select Panchayat MC's</option>
                            </select>
                        </div>
                        <div class="col-lg-4 form-group"> 
                            <label for="exampleInputEmail1">Ward No.</label>
                            <span class="fa fa-asterisk"></span>
                            <select name="ward" class="form-control select2" id="value_div_id" onchange="callAjax(this,'{{ route('admin.Master.WardWiseBooth') }}'+'?ward_id='+this.value+'&village_id='+$('#village_select_box').val(),'booth_select_box')">
                                <option selected disabled>Select Ward</option> 
                            </select>
                        </div>
                        <div class="col-lg-4 form-group"> 
                            <label for="exampleInputEmail1">Booth No.</label>
                            <span class="fa fa-asterisk"></span>
                            <select name="booth" class="form-control select2" id="booth_select_box">
                                <option selected disabled>Select Booth No.</option> 
                            </select>
                        </div>
                        <div class="col-lg-4 form-group"> 
                            <label for="exampleInputEmail1">Slip Per Page</label>
                            <span class="fa fa-asterisk"></span>
                            <select name="slip_per_page" class="form-control select2">
                                <option value="{{Crypt::encrypt(1)}}">2</option> 
                                <option value="{{Crypt::encrypt(2)}}">4</option> 
                                <option value="{{Crypt::encrypt(3)}}">10</option> 
                            </select>
                        </div>
                    </div>
                    <div class="card-footer text-center">
                        <button type="submit" class="btn btn-primary btn-block">Voter Slip Generate</button>
                    </div>
                </form>
            </div>
        </div> 
    </div>
</section>
@endsection


