@extends('admin.layout.base')
@section('body')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h3>Prepare Voter List Municipal Committee / Council/ Cooperations</h3>
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
                        <form action="{{ route('admin.voter.GenerateVoterListAll') }}" method="post" no-reset="true" class="add_form">
                            {{ csrf_field() }}
                            <div class="card-body">
                                <div class="row">  
                                    <div class="col-lg-3 form-group">
                                        <label for="exampleInputEmail1">District</label>
                                        <span class="fa fa-asterisk"></span>
                                        <select name="district" class="form-control select2" id="district_select_box" onchange="callAjax(this,'{{ route('admin.Master.DistrictWiseBlock',2) }}','block_select_box')">
                                            <option selected disabled>Select District</option>
                                            @foreach ($Districts as $District)
                                            <option value="{{ $District->id }}">{{ $District->code }}--{{ $District->name_e }}</option>  
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-lg-3 form-group">
                                    <label for="exampleInputEmail1">MC's</label>
                                    <span class="fa fa-asterisk"></span>
                                    <select name="block" class="form-control select2" id="block_select_box" onchange="callAjax(this,'{{ route('admin.Master.BlockWiseVillage') }}'+'?id='+this.value+'&district_id='+$('#district_select_box').val(),'village_select_box')">
                                        <option selected disabled>Select MC's</option> 
                                    </select>
                                    </div>
                                    <div class="col-lg-3 form-group">
                                        <label for="exampleInputEmail1">MC's</label>
                                        <span class="fa fa-asterisk"></span>
                                        <select name="village" class="form-control select2" id="village_select_box" multiselect-form="true" onchange="callAjax(this,'{{ route('admin.voter.VillageWiseWardMultiple') }}','value_div_id')">
                                            <option selected disabled>Select MC's</option>
                                        </select>
                                    </div>
                                    <div class="col-lg-3 form-group"> 
                                    <label for="exampleInputEmail1">Ward No.</label>
                                    <span class="fa fa-asterisk"></span>
                                    <select name="ward" class="form-control multiselect" id="value_div_id">
                                      <option selected disabled>Select Ward</option> 
                                    </select>
                                    </div>
                                    <div class="col-lg-12 form-group text-center">
                                      <input type="hidden" name="booth" value="0">   
                                    </div>
                                    <input type="hidden" name="proses_by" id="proses_by" value="0">
                                    
                                    <div class="col-lg-4 form-group"> 
                                        <label for="exampleInputEmail1">List Prepare Option</label>
                                        <span class="fa fa-asterisk"></span>
                                        <select name="list_prepare_option" class="form-control select2" id="list_prepare_option">
                                            <option selected disabled>Select Option</option>
                                            @foreach ($rslistPrepareOption as $list_prepare_option)
                                            <option value="{{ $list_prepare_option->id }}">{{ $list_prepare_option->option_name }}</option>  
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-lg-4 form-group"> 
                                        <label for="exampleInputEmail1">List Sorting Option</label>
                                        <span class="fa fa-asterisk"></span>
                                        <select name="list_sorting_option" class="form-control select2" id="list_sorting_option">
                                            <option selected disabled>Select Option</option>
                                            @foreach ($rslistSortingOption as $list_prepare_option)
                                            <option value="{{ $list_prepare_option->id }}">{{ $list_prepare_option->option_name }}</option>  
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-lg-4 form-group" style="margin-top: 33px">
                                       <input type="submit" class="btn btn-success form-control" value="Process And Lock" onclick="$('#proses_by').val(1)">
                                    </div>
                                    
                                    </div>
                                </div> 
                        </form>
                    </div> 
                </div>
            </div>
      </div>
  </div>
</section>
@endsection
@push('scripts')
 
@endpush
 

