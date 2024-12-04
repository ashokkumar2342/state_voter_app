@extends('admin.layout.base')
@section('body')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h3>Voter Slip Download</h3>
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
                                <div class="row"> 
                                    <div class="col-lg-6 form-group">
                                        <label for="exampleInputEmail1">District</label>
                                        <span class="fa fa-asterisk"></span>
                                        <select name="district" class="form-control select2" id="district_select_box" onchange="callAjax(this,'{{ route('admin.Master.DistrictWiseBlock') }}','block_select_box')">
                                            <option selected disabled>Select District</option>
                                            @foreach ($Districts as $District)
                                            <option value="{{ $District->id }}">{{ $District->code }}--{{ $District->name_e }}</option>  
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-lg-6 form-group">
                                        <label for="exampleInputEmail1">Block / MC's</label>
                                        <span class="fa fa-asterisk"></span>
                                        <select name="block" class="form-control select2" id="block_select_box" onchange="callAjax(this,'{{ route('admin.prepare.voter.slip.download.result') }}'+'?block_id='+this.value,'download_table')">
                                            <option selected disabled>Select Block MCS</option> 
                                        </select>
                                    </div> 
                                </div>
                                <div id="download_table">
                                     
                                </div> 
                        </div>
                    </div> 
                </div>
            </div>
        </div>
</div>
</section>
@endsection
@push('scripts')
 
@endpush
 

