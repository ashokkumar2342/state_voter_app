@extends('admin.layout.base')
@section('body')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h3>Panchayat / MC's Wards</h3>
            </div>
        </div> 
    </div>
    <div class="card card-info">
        <div class="card-body">
            <form action="{{ route('admin.Master.ward.store') }}" method="post" class="add_form" no-reset="true" select-triger="village_select_box">
            {{ csrf_field() }}
                <div class="row">
                    <div class="col-lg-3 form-group">
                        <label for="exampleInputEmail1">District</label>
                        <span class="fa fa-asterisk"></span>
                        <select name="district" class="form-control select2" id="district_select_box" onchange="callAjax(this,'{{ route('admin.Master.DistrictWiseBlock') }}','block_select_box')" required>
                            <option selected disabled>Select District</option>
                            @foreach ($rs_district as $rs_val)
                                <option value="{{ Crypt::encrypt($rs_val->opt_id) }}">{{ $rs_val->opt_text }}</option>  
                            @endforeach
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
                    <div class="col-lg-3 form-group">
                        <label for="exampleInputEmail1">Wards To Be Created</label>
                        <span class="fa fa-asterisk"></span>
                        <input type="text" name="ward" class="form-control" placeholder="" maxlength="2" required onkeypress='return event.charCode >= 48 && event.charCode <= 57'>
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


