@extends('admin.layout.base')
@section('body')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h3>Unlock Voter List (All Booths in Ward)</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right"> 
                </ol>
            </div>
        </div> 
        <div class="card card-info"> 
            <div class="card-body">  
                <form action="{{ route('admin.UnlockVoterList.ward.unlock') }}" method="post" no-reset="true" class="add_form">
                    {{ csrf_field() }}                            
                    <div class="row">  
                        <div class="col-lg-4 form-group">
                            <label for="exampleInputEmail1">District</label>
                            <span class="fa fa-asterisk"></span>
                            <select name="district" class="form-control select2" id="district_select_box" onchange="callAjax(this,'{{ route('admin.Master.DistrictWiseBlock',2) }}','block_select_box')">
                                <option selected disabled>Select District</option>
                                @foreach ($rs_district as $rs_val)
                                    <option value="{{ Crypt::encrypt($rs_val->opt_id) }}">{{ $rs_val->opt_text }}</option>  
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-4 form-group">
                            <label for="exampleInputEmail1">MC's</label>
                            <span class="fa fa-asterisk"></span>
                            <select name="block" class="form-control select2" id="block_select_box" onchange="callAjax(this,'{{ route('admin.Master.BlockWiseVillage') }}'+'?id='+this.value+'&district_id='+$('#district_select_box').val(),'village_select_box')">
                                <option selected disabled>Select MC's</option> 
                            </select>
                        </div>
                        <div class="col-lg-4 form-group">
                            <label for="exampleInputEmail1">MC's</label>
                            <span class="fa fa-asterisk"></span>
                            <select name="village" class="form-control select2" id="village_select_box" multiselect-form="true" onchange="callAjax(this,'{{ route('admin.voter.VillageWiseWardMultiple') }}','value_div_id')">
                                <option selected disabled>Select MC's</option>
                            </select>
                        </div>
                        <div class="col-lg-6 form-group"> 
                            <label for="exampleInputEmail1">Ward No.</label>
                            <span class="fa fa-asterisk"></span>
                            <select name="ward" class="form-control select2" id="value_div_id">
                                <option selected disabled>Select Ward</option> 
                            </select>
                        </div>
                    </div>
                    <div class="card-footer text-center">
                        <input type="submit" class="btn btn-danger form-control" value="Unlock">
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection


