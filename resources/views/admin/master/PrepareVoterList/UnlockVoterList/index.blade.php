@extends('admin.layout.base')
@section('body')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h3>Unlock Entry (Panchayat)</h3>
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
                        <form action="{{ route('admin.UnlockVoterList.unlock') }}" method="post" no-reset="true" class="add_form">
                            {{ csrf_field() }}
                            <div class="card-body">
                                <div class="row">  
                                    <div class="col-lg-4 form-group">
                                        <label for="exampleInputEmail1">District</label>
                                        <span class="fa fa-asterisk"></span>
                                        <select name="district" class="form-control select2" id="district_select_box" onchange="callAjax(this,'{{ route('admin.Master.DistrictWiseBlock',1) }}','block_select_box')">
                                            <option selected disabled>Select District</option>
                                            @foreach ($Districts as $District)
                                            <option value="{{ $District->id }}">{{ $District->code }}--{{ $District->name_e }}</option>  
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-lg-4 form-group">
                                    <label for="exampleInputEmail1">Block</label>
                                    <span class="fa fa-asterisk"></span>
                                    <select name="block" class="form-control select2" id="block_select_box" onchange="callAjax(this,'{{ route('admin.Master.BlockWiseVillage') }}'+'?id='+this.value+'&district_id='+$('#district_select_box').val(),'village_select_box')">
                                        <option selected disabled>Select Block</option> 
                                    </select>
                                    </div>
                                    <div class="col-lg-4 form-group">
                                        <label for="exampleInputEmail1">Panchayat</label>
                                        <span class="fa fa-asterisk"></span>
                                        <select name="village" class="form-control select2" id="village_select_box" multiselect-form="true">
                                            <option selected disabled>Select Panchayat</option>
                                        </select>
                                    </div>
                                    <input type="hidden" name="ward" value="0"> 
                                    <input type="hidden" name="booth" value="0"> 
                                   <div class="col-lg-12 form-group">
                                       <input type="submit" class="btn btn-danger form-control" value="Unlock">
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
 

