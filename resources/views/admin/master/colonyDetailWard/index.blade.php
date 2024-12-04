@extends('admin.layout.base')
@section('body')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h3>Colony Detail (Ward Wise)</h3>
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
                        <select name="district" class="form-control select2" id="district_select_box" onchange="callAjax(this,'{{ route('admin.Master.DistrictWiseBlock',0) }}','block_select_box')">
                            <option selected disabled>Select District</option>
                            @foreach ($Districts as $District)
                            <option value="{{ $District->id }}">{{ $District->code }}--{{ $District->name_e }}</option>  
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-4 form-group">
                        <label for="exampleInputEmail1">MC's</label>
                        <span class="fa fa-asterisk"></span>
                        <select name="block" class="form-control select2" id="block_select_box" onchange="callAjax(this,'{{ route('admin.Master.BlockWiseVillage') }}'+'?id='+this.value+'&district_id='+$('#district_select_box').val(),'village_select_box')" select-triger="village_select_box">
                            <option selected disabled>Select MC</option> 
                        </select>
                    </div>
                    <div class="col-lg-4 form-group">
                        <label for="exampleInputEmail1">MC Name</label>
                        <span class="fa fa-asterisk"></span>
                        <select name="village" class="form-control select2" id="village_select_box" onchange="callAjax(this,'{{ route('admin.master.list.ward.colonydetail') }}'+'?village='+this.value ,'last_voter_srno_list')">
                            <option selected disabled>Select MC</option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12" id="last_voter_srno_list">
                        
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
@push('scripts')

@endpush


