@extends('admin.layout.base')
@section('body')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h3>Panchayat / MC's</h3>
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
                            <form action="{{ route('admin.Master.village.store') }}" method="post" class="add_form" select-triger="block_select_box" no-reset="true" button-click="btn_click_by_form" reset-input-text="code,name_english,name_local_language,ward">
                                {{ csrf_field() }} 
                                    <div class="row"> 
                                    <div class="col-lg-4 form-group">
                                        <label for="exampleInputEmail1">States</label>
                                        <span class="fa fa-asterisk"></span>
                                        <select name="states" id="state_select_box" class="form-control" onchange="callAjax(this,'{{ route('admin.Master.stateWiseDistrict') }}','district_select_box')">
                                            <option selected disabled>Select States</option>
                                            @foreach ($States as $State)
                                            <option value="{{ $State->id }}">{{ $State->code }}--{{ $State->name_e }}</option>  
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-lg-4 form-group">
                                        <label for="exampleInputEmail1">District</label>
                                        <span class="fa fa-asterisk"></span>
                                        <select name="district" class="form-control" id="district_select_box" onchange="callAjax(this,'{{ route('admin.Master.DistrictWiseBlock') }}','block_select_box')">
                                            <option selected disabled>Select District</option>
                                        </select>
                                    </div>
                                    <div class="col-lg-4 form-group">
                                        <label for="exampleInputEmail1">Block / MC's</label>
                                        <span class="fa fa-asterisk"></span>
                                        <select name="block_mcs" class="form-control" id="block_select_box" data-table="district_table" onchange="callAjax(this,'{{ route('admin.Master.villageTable') }}','village_table')">
                                            <option selected disabled>Select Block / MC's</option>
                                             
                                        </select>
                                    </div>
                                    <div class="col-lg-3 form-group">
                                        <label for="exampleInputEmail1">Panchayat / MC's Code</label>
                                        <span class="fa fa-asterisk"></span>
                                        <input type="text" name="code" id="code" class="form-control" placeholder="Enter Code" maxlength="5" required>
                                    </div>
                                    <div class="col-lg-3 form-group">
                                        <label for="exampleInputPassword1">Panchayat / MC's Name (English)</label>
                                        <span class="fa fa-asterisk"></span>
                                        <input type="text" name="name_english" id="name_english" class="form-control" placeholder="Enter Name (English)" maxlength="50" required>
                                    </div>
                                    <div class="col-lg-3 form-group">
                                        <label for="exampleInputPassword1">Panchayat / MC's Name (Hindi)</label>
                                        <span class="fa fa-asterisk"></span>
                                        <input type="text" name="name_local_language" id="name_local_language" class="form-control" placeholder="Enter Name (Hindi)" maxlength="100" required>
                                    </div>
                                    <div class="col-lg-3 form-group">
                                        <label for="exampleInputPassword1">Ward To Be Created</label>

                                        <span class="fa fa-asterisk"></span>
                                        <input type="text" name="ward" id="ward" class="form-control"maxlength="2" onkeypress='return event.charCode >= 48 && event.charCode <= 57' required>
                                    </div> 
                                    
                                </div> 
                                <div class="card-footer text-center">
                                    <button type="submit" class="btn btn-primary form-control">Submit</button>
                                </div>
                            </form> 
                    </div>
                    <div class="col-lg-12" id="village_table">
                        <div class="card card-primary table-responsive"> 
                            <table id="village_table" class="table table-striped table-hover control-label">
                                <thead>
                                    <tr>
                                        <th class="text-nowrap">Code</th>
                                        <th class="text-nowrap">Name (English)</th>
                                        <th class="text-nowrap">Name (Hindi)</th>
                                        <th class="text-nowrap">Total Ward</th>
                                        <th class="text-nowrap">Action</th>

                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table> 
                    </div> 
                </div>
            </div> 
        </div> 
    </section>
    @endsection
    
