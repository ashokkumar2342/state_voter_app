@extends('admin.layout.base')
@section('body')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h3>Panchayat / MC's</h3>
            </div>
        </div> 
    </div>
    <div class="card card-info">
        <div class="card-body">
            <form action="{{ route('admin.VoterListMaster.store', Crypt::encrypt(0)) }}" method="post" no-reset="true" class="add_form" no-reset="true" select-triger="block_select_box" reset-input-text="voter_list_name,voter_list_type,publication_year,base_year,date_of_publication,base_date,remarks1,remarks2,remarks3">
            {{ csrf_field() }}
                <div class="row">
                    <div class="col-lg-6 form-group">
                        <label for="exampleInputEmail1">District</label>
                        <span class="fa fa-asterisk"></span>
                        <select name="district" class="form-control select2" id="district_select_box" onchange="callAjax(this,'{{ route('admin.Master.DistrictWiseBlock') }}','block_select_box')" required>
                            <option selected disabled>Select District</option>
                            @foreach ($rs_district as $rs_val)
                                <option value="{{ Crypt::encrypt($rs_val->opt_id) }}">{{ $rs_val->opt_text }}</option>  
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-6 form-group">
                        <label for="exampleInputEmail1">Block / MC's</label>
                        <span class="fa fa-asterisk"></span>
                        <select name="block" class="form-control select2" id="block_select_box" data-table-new-without-pagination="ajax_data_table" onchange="callAjax(this,'{{ route('admin.VoterListMaster.table') }}','result_div_id')" required>
                            <option selected disabled>Select Block / MC's</option>                           
                        </select>
                    </div>
                    <div class="col-lg-4 form-group">
                        <label for="exampleInputEmail1">Voter List Name</label>
                        <span class="fa fa-asterisk"></span>
                        <input type="text" name="voter_list_name" class="form-control" maxlength="200" required>
                    </div>
                    <div class="col-lg-4 form-group">
                        <label for="exampleInputEmail1">Voter List Type</label>
                        <span class="fa fa-asterisk"></span>
                        <input type="text" name="voter_list_type" class="form-control" maxlength="200" required>
                    </div>
                    <div class="col-lg-4 form-group">
                        <label for="exampleInputEmail1">Publication Year</label>
                        <span class="fa fa-asterisk"></span>
                          <input type="text" name="publication_year" class="form-control" maxlength="4" onkeypress='return event.charCode >= 48 && event.charCode <= 57' required>
                    </div>
                    <div class="col-lg-4 form-group">
                        <label for="exampleInputEmail1">Date of Publication</label>
                        <input type="text" name="date_of_publication" class="form-control" maxlength="20" required>
                    </div>
                    <div class="col-lg-4 form-group">
                        <label for="exampleInputEmail1">Base Year</label>
                        <input type="text" name="base_year" class="form-control"
                        maxlength="4" onkeypress='return event.charCode >= 48 && event.charCode <= 57' required>
                    </div>
                    <div class="col-lg-4 form-group">
                        <label for="exampleInputEmail1">Base Date</label>
                        <input type="text" name="base_date" class="form-control" maxlength="20" required>
                    </div>
                    <div class="col-lg-4 form-group">
                        <label for="exampleInputEmail1">पुनरिक्षण का विवरण</label>
                        <input type="text" name="remarks1" class="form-control" maxlength="100" required>
                    </div>
                    <div class="col-lg-4 form-group">
                        <label for="exampleInputEmail1">Remarks 1</label>
                        <input type="text" name="remarks2" class="form-control" maxlength="100">
                    </div>
                    <div class="col-lg-4 form-group">
                        <label for="exampleInputEmail1">Remarks 2</label>
                        <input type="text" name="remarks3" class="form-control" maxlength="100">
                    </div>
                    <div class="col-lg-4 form-group">  
                        <div class="icheck-primary d-inline">
                            <input type="checkbox" id="radioPrimary3" name="is_supplement" value="1" >
                            <label for="radioPrimary3">Is Supplement</label>  
                        </div>
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


