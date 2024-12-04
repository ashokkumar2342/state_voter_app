@extends('admin.layout.base')
@section('body')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h3>Delete And Restore</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right"> 
                </ol>
            </div>
        </div>
        <div class="card card-info"> 
            <div class="card-body">
                <form action="{{ route('admin.voter.DeteleAndRestoreShow') }}" method="post" class="add_form" data-table="voter_datatable" success-content-id="voter_table" no-reset="true">
                    {{ csrf_field() }}
                    <div class="row">  
                        <div class="col-lg-3 form-group">
                            <label for="exampleInputEmail1">District</label>
                            <span class="fa fa-asterisk"></span>
                            <select name="district" class="form-control" id="district_select_box" onchange="callAjax(this,'{{ route('admin.Master.DistrictWiseBlock') }}','block_select_box')">
                                <option selected disabled>Select District</option>
                                @foreach ($Districts as $District)
                                <option value="{{ $District->id }}">{{ $District->code }}--{{ $District->name_e }}</option>  
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-3 form-group">
                            <label for="exampleInputEmail1">Block/ MC's</label>
                            <span class="fa fa-asterisk"></span>
                            <select name="block" class="form-control" id="block_select_box" onchange="callAjax(this,'{{ route('admin.Master.BlockWiseVillage') }}'+'?id='+this.value+'&district_id='+$('#district_select_box').val(),'village_select_box')">
                                <option selected disabled>Select Block/ MC's</option> 
                            </select>
                        </div> 
                        <div class="col-lg-3 form-group">
                            <label for="exampleInputEmail1">Panchayat</label>
                            <span class="fa fa-asterisk"></span>
                            <select name="village" class="form-control" id="village_select_box" onchange="callAjax(this,'{{ route('admin.voter.VillageWiseWard') }}','ward_no_select_box')">
                                <option selected disabled>Select Panchayat</option> 
                            </select>
                        </div>
                        <div class="col-lg-3 form-group">
                            <label for="exampleInputEmail1">Ward No.</label>
                            <span class="fa fa-asterisk"></span>
                            <select name="ward_no" class="form-control" id="ward_no_select_box" >
                                <option selected disabled>Select Ward No.</option> 
                            </select>
                        </div>
                        <div class="col-lg-4 form-group">
                            <label for="exampleInputEmail1">Print Sr.No.</label>
                            <input type="text" name="print_sr_no" class="form-control"> 
                        </div>
                        <div class="col-lg-4 form-group">
                            <label for="exampleInputEmail1">Name</label>
                            <input type="text" name="name" class="form-control"> 
                        </div>
                        <div class="col-lg-4 form-group">
                            <label for="exampleInputEmail1">F/H Name</label>
                            <input type="text" name="father_name" class="form-control"> 
                        </div>
                        <div class="col-lg-12 form-group">
                             <input type="submit" id="btn_show" value="Show" class="form-control btn btn-success">
                        </div>
                    </div>
                </form>
                <div id="voter_table">
                    
                </div>
            </div>
        </div>
    </div> 
</section>
@endsection 

