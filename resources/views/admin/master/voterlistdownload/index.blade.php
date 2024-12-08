@extends('admin.layout.base')
@section('body')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h3>Voter List Download</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right"> 
                </ol>
            </div>
        </div> 
        <div class="card card-info"> 
            <div class="card-body">  
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
                        <select name="block" class="form-control select2" id="block_select_box" onchange="callAjax(this,'{{ route('admin.Master.BlockWiseVoterListType') }}','voter_list_master_id')">
                            <option selected disabled>Select Block MCS</option> 
                        </select>
                    </div>
                    <div class="col-lg-4 form-group">
                        <label for="exampleInputEmail1">Voter List</label>
                        <span class="fa fa-asterisk"></span>
                        <select name="voter_list_master_id" class="form-control select2" id="voter_list_master_id" data-table-new-without-pagination="ajax_data_table" onchange="callAjax(this,'{{ route('admin.voter.BlockWiseDownloadTable') }}'+'?block_id='+$('#block_select_box').val()+'&state_id='+$('#state_id').val()+'&district_id='+$('#district_select_box').val()+'&voter_list_master_id='+$('#voter_list_master_id').val(),'download_table')">
                            <option selected disabled>Select Voter List</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="card card-info"> 
            <div class="card-body">
                <div class="row" id="download_table"></div>
            </div>
        </div>   
    </div>
</div>
</section>
@endsection


