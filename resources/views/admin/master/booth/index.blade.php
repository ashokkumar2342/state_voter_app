@extends('admin.layout.base')
@section('body')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h3>Polling Booths</h3>
            </div>
        </div> 
    </div>
    <div class="card card-info">
        <div class="card-body">
            <form action="{{ route('admin.Master.booth.store', Crypt::encrypt(0)) }}" method="post" class="add_form" no-reset="true" reset-input-text="booth_no,booth_name_english,booth_name_local,booth_no_c" select-triger="village_select_box">
            {{ csrf_field() }}
                <div class="row">
                    <div class="col-lg-4 form-group">
                        <label for="exampleInputEmail1">District</label>
                        <span class="fa fa-asterisk"></span>
                        <select name="district" class="form-control select2" id="district_select_box" onchange="callAjax(this,'{{ route('admin.Master.DistrictWiseBlock') }}','block_select_box')" required>
                            <option selected disabled>Select District</option>
                            @foreach ($rs_district as $rs_val)
                                <option value="{{ Crypt::encrypt($rs_val->opt_id) }}">{{ $rs_val->opt_text }}</option>  
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-4 form-group">
                        <label for="exampleInputEmail1">Block / MC's</label>
                        <span class="fa fa-asterisk"></span>
                        <select name="block" class="form-control select2" id="block_select_box" onchange="callAjax(this,'{{ route('admin.Master.BlockWiseVillage') }}'+'?id='+this.value+'&district_id='+$('#district_select_box').val(),'village_select_box')" required>
                            <option selected disabled>Select Block / MC's</option> 
                        </select>
                    </div>
                    <div class="col-lg-4 form-group">
                        <label for="exampleInputEmail1">Panchayat / MC's</label>
                        <span class="fa fa-asterisk"></span>
                        <select name="village" class="form-control select2" id="village_select_box" select2="true" data-table-new-without-pagination="ajax_data_table" onchange="callAjax(this,'{{ route('admin.Master.booth.table') }}','result_div_id')" required>
                            <option selected disabled>Select Panchayat / MC's</option>
                            
                        </select>
                    </div>
                    <div class="col-lg-3 form-group">
                        <label for="exampleInputEmail1">Polling Booth No.</label>
                        <span class="fa fa-asterisk"></span>
                        <input type="text" name="booth_no" id="booth_no" class="form-control" placeholder="" maxlength="5" required onkeypress='return event.charCode >= 48 && event.charCode <= 57'>
                    </div>
                    <div class="col-lg-3 form-group">
                        <label for="exampleInputEmail1">Booth No. (Auxiliary)</label>
                         
                        <input type="text" name="booth_no_c" id="booth_no_c" class="form-control" placeholder="" maxlength="1">
                    </div>
                    <div class="col-lg-3 form-group">
                        <label for="exampleInputEmail1">Booth Name (English) </label>
                        <span class="fa fa-asterisk"></span>
                        <input type="text" name="booth_name_english" id="booth_name_english" class="form-control" placeholder="" maxlength="100" required>
                    </div>
                    <div class="col-lg-3 form-group">
                        <label for="exampleInputEmail1">Booth Name (Hindi)</label>
                        <span class="fa fa-asterisk"></span>
                        <input type="text" name="booth_name_local" id="booth_name_local" class="form-control" placeholder="" maxlength="100" required>
                    </div>
                    <div class="col-lg-6 form-group">
                        <label for="exampleInputEmail1">Booth Area (English) </label>
                        <input type="text" name="booth_area_english" id="booth_name_english" class="form-control" placeholder="" maxlength="250">
                    </div>
                    <div class="col-lg-6 form-group">
                        <label for="exampleInputEmail1">Booth Area (Hindi)</label>
                        <input type="text" name="booth_area_local" id="booth_name_local" class="form-control" placeholder="" maxlength="250">
                    </div>                    
                </div>
                <div class="card-footer text-center">
                    <button type="submit" class="btn btn-primary btn-block">Submit</button>
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


