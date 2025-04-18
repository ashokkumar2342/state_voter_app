@extends('admin.layout.base')
@section('body')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-9">
                <h3>Mapping (Panchayat / MC's Ward - Polling  Booth)</h3>
            </div>
            <div class="col-sm-3">
                <ol class="breadcrumb float-sm-right"> 
                </ol>
            </div>
        </div> 
        <div class="card card-info"> 
            <div class="card-body">
                <form action="{{ route('admin.Master.MappingWardBoothStore') }}" method="post" class="add_form" no-reset="true" select-triger="ward_select_box" reset-input-text="from_sr_no,to_sr_no">
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
                            <label for="exampleInputEmail1">Panchayat MC's</label>
                            <span class="fa fa-asterisk"></span>
                            <select name="village" class="form-control select2" id="village_select_box" select2="true" data-table="ward_datatable" onchange="callAjax(this,'{{ route('admin.voter.VillageWiseWardAll') }}','ward_select_box')">
                                <option selected disabled>Select Panchayat MC's</option>
                            </select>
                        </div>
                        <div class="col-lg-3 form-group">
                            <label for="exampleInputEmail1">Ward</label>
                            <span class="fa fa-asterisk"></span>
                            <select name="ward" class="form-control select2" id="ward_select_box"  onchange="callAjax(this,'{{ route('admin.Master.MappingWardBoothTable') }}'+'?village_id='+$('#village_select_box').val(),'booth_table');callAjax(this,'{{ route('admin.Master.MappingWardBoothSelectBooth') }}'+'?village_id='+$('#village_select_box').val(),'booth_select_box')">
                                <option selected disabled>Select Ward</option>
                            </select>
                        </div>
                    </div>
                    <div class="row" id="booth_table"></div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card card-info">
                                <div class="card-header">
                                    <h3 class="card-title">Add New Booth</h3>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-lg-4 form-group">
                                            <label for="exampleInputEmail1">Booth</label>
                                            <span class="fa fa-asterisk"></span>
                                            <select name="booth" class="form-control select2" id="booth_select_box">
                                                <option selected disabled>Select Booth</option>
                                            </select>
                                        </div>
                                        <div class="col-lg-4 form-group">
                                            <label>From Sr. No.</label>
                                            <span class="fa fa-asterisk"></span>
                                            <input type="text" name="from_sr_no" id="from_sr_no" class="form-control" required onkeypress='return event.charCode >= 48 && event.charCode <= 57' maxlength="5" >   
                                        </div>
                                        <div class="col-lg-4 form-group">
                                            <label>To Sr. No.</label>
                                            <span class="fa fa-asterisk"></span>
                                            <input type="text" name="to_sr_no" id="to_sr_no" class="form-control" required onkeypress='return event.charCode >= 48 && event.charCode <= 57' maxlength="5" >
                                        </div>
                                    </div> 
                                </div> 
                            </div>
                        </div>
                    </div>
                    <div class="card-footer text-center">
                        <button type="submit" class="btn btn-primary btn-block">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection


