@extends('admin.layout.base')
@section('body')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h3>Assembly Constituency</h3>
            </div>
        </div> 
    </div>
    <div class="card card-info">
        <div class="card-body">
            <form action="{{ route('admin.Master.AssemblyPart.store', Crypt::encrypt(0)) }}" method="post" class="add_form" no-reset="true" select-triger="assembly_select_box">
            {{ csrf_field() }} 
                <div class="form-group">
                    <label for="exampleInputEmail1">District</label>
                    <span class="fa fa-asterisk"></span>
                    <select name="district" class="form-control select2" id="district_select_box" onchange="callAjax(this,'{{ route('admin.voter.districtWiseAssembly') }}','assembly_select_box');">
                        <option selected disabled>Select District</option>
                        @foreach ($rs_district as $rs_val)
                            <option value="{{ Crypt::encrypt($rs_val->opt_id) }}">{{ $rs_val->opt_text }}</option>  
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="exampleInputEmail1">Assembly</label>
                    <span class="fa fa-asterisk"></span>
                    <select name="assembly" class="form-control select2" id="assembly_select_box" data-table-new-without-pagination="ajax_data_table" onchange="callAjax(this,'{{ route('admin.Master.AssemblyPartTable') }}','result_div_id')">
                        <option selected disabled>Select Assembly</option>                         
                    </select>
                </div>
                <div class="form-group">
                    <label for="exampleInputEmail1">How Many Parts To Be Created </label>
                    <span class="fa fa-asterisk"></span>
                    <input type="text" name="part_no" class="form-control" placeholder="Total Parts No." maxlength="3" onkeypress='return event.charCode >= 48 && event.charCode <= 57'>
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


