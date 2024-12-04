@extends('admin.layout.base')
@section('body')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h3>Check Voter Status</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right"> 
                </ol>
            </div>
        </div> 
        <div class="card card-info"> 
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card-body">
                        <form action="{{ route('admin.report.check.voter.status.search') }}" method="post" class="add_form" success-content-id="value_div_id" no-reset="true">
                            <div class="row"> 
                            <div class="col-lg-3 form-group">
                            <label for="exampleInputEmail1">States</label>
                            <span class="fa fa-asterisk"></span>
                            <select name="states" id="state_id" class="form-control select2" onchange="callAjax(this,'{{ route('admin.Master.stateWiseDistrict') }}','district_select_box')">
                            <option selected disabled>Select States</option>
                            @foreach ($states as $State)
                            <option value="{{ $State->id }}">{{ $State->code }}--{{ $State->name_e }}</option>  
                            @endforeach
                            </select>
                            </div>
                            <div class="col-lg-3 form-group">
                            <label for="exampleInputEmail1">District</label>
                            <span class="fa fa-asterisk"></span>
                            <select name="district" class="form-control select2" id="district_select_box" onchange="callAjax(this,'{{ route('admin.Master.DistrictWiseBlock') }}','block_select_box')">
                            <option selected disabled>Select District</option>
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
                                <select name="village_id" class="form-control select2" id="village_select_box">
                                    <option selected disabled>Select Panchayat / MC's</option>
                                    
                                </select>
                            </div>
                            <div class="col-lg-6 form-group">
                                <label for="exampleInputEmail1">Voter Card No.</label>
                                <span class="fa fa-asterisk"></span>
                                <input type="text" name="voter_card_no" class="form-control" maxlength="15" placeholder="Enter Voter Card No. ">
                            </div>
                            <div class="col-lg-6 form-group" style="margin-top: 30px"> 
                             <button type="submit" class="form-control btn btn-info"><i class="fa fa-search"></i> Search </button>
                            </div>
                        </div>
                        <div class="row" id="value_div_id">
                              
                        </div> 
                     </form>       
                    </div>
                     
                </div>
            </div> 
        </div> 
    </section>
    @endsection
<script type="text/javascript">
    function disabledataList() {
    document.getElementById("data_list").disabled = false;
}

</script>


   