@extends('admin.layout.base')
@section('body')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h3>Block / MC's</h3>
            </div>
        </div> 
    </div>
    <div class="card card-info">
        <div class="card-body">
            <form action="{{ route('admin.master.block.mcs.store', Crypt::encrypt(0)) }}" method="post" class="add_form" no-reset="true" select-triger="district_select_box" reset-input-text="code,name_english,name_local_language,block_mc_type,ps_ward">
            {{ csrf_field() }} 
                <div class="row">
                    <div class="col-lg-12 form-group">
                        <label for="exampleInputEmail1">District</label>
                        <span class="fa fa-asterisk"></span>
                        <select name="district" class="form-control select2" id="district_select_box" data-table-new-without-pagination="ajax_data_table" onchange="callAjax(this,'{{ route('admin.master.block.mcs.table') }}', 'result_div_id')">
                            <option selected disabled>Select District</option>
                            @foreach ($rs_district as $rs_val)
                                <option value="{{ Crypt::encrypt($rs_val->opt_id) }}">{{ $rs_val->opt_text }}</option>  
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-3 form-group">
                        <label for="exampleInputEmail1">Block / MC's Code</label>
                        <span class="fa fa-asterisk"></span>
                        <input type="text" name="code" id="code" class="form-control" placeholder="Enter Code" maxlength="5" required>
                    </div>
                    <div class="col-lg-3 form-group">
                        <label for="exampleInputPassword1">Block / MC's Name(English)</label>
                        <span class="fa fa-asterisk"></span>
                        <input type="text" name="name_english" id="name_english" class="form-control" placeholder="Enter Name (English)" maxlength="50" required>
                    </div>
                    <div class="col-lg-3 form-group">
                        <label for="exampleInputPassword1">Block / MC's Name(Hindi)</label>
                        <span class="fa fa-asterisk"></span>
                        <input type="text" name="name_local_language" id="name_local_language" class="form-control" placeholder="Enter Name (In Hindi)" maxlength="100" required>
                    </div>
                    <div class="col-lg-3 form-group">
                        <label for="exampleInputPassword1">Block / MC's Type</label>
                        <span class="fa fa-asterisk"></span>
                        <select name="block_mc_type_id" id="block_mc_type" class="form-control select2">
                            <option selected disabled>Select Block / MC's Type</option>
                            @foreach ($rs_block_mc_type as $rs_val)
                                <option value="{{ Crypt::encrypt($rs_val->id) }}">{{ $rs_val->block_mc_type_e }}</option>  
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


