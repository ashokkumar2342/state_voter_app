@extends('admin.layout.base')
@section('body')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h3>Districts</h3>
            </div>
        </div> 
    </div>
    <div class="card card-info">
        <div class="card-body">
            <form action="{{ route('admin.master.districts.store', Crypt::encrypt(0)) }}" method="post" class="add_form"  no-reset="true" reset-input-text="code,name_english,zp_ward,name_local_language" select-triger="state_select_box">
            {{ csrf_field() }} 
                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="exampleInputEmail1">States</label>
                            <span class="fa fa-asterisk"></span>
                            <select name="states" class="form-control select2" id="state_select_box" data-table-new-without-pagination="ajax_data_table"  onchange="callAjax(this,'{{ route('admin.master.districts.table') }}','result_div_id')">    
                                <option selected disabled>Select State</option>
                                @foreach ($States as $State)
                                    <option value="{{ Crypt::encrypt($State->id) }}">{{ $State->code }}--{{ $State->name_e }}</option>  
                                @endforeach
                            </select>
                        </div>                               
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="exampleInputEmail1">District Code</label>
                            <span class="fa fa-asterisk"></span>
                            <input type="text" name="code" id="code" class="form-control" placeholder="Enter Code" maxlength="5" required>
                        </div>                               
                    </div>
                    <div class="col-lg-4">
                        <div class="form-group">
                            <label for="exampleInputPassword1">District Name (English)</label>
                            <span class="fa fa-asterisk"></span>
                            <input type="text" name="name_english" id="name_english" class="form-control" placeholder="Enter Name (English)" maxlength="50" required>
                        </div>                               
                    </div>
                    <div class="col-lg-4">
                        <div class="form-group">
                            <label for="exampleInputPassword1">District Name (Hindi)</label>
                            <span class="fa fa-asterisk"></span>
                            <input type="text" name="name_local_language" id="name_local_language" class="form-control" placeholder="Enter Name (In hindi)" maxlength="100" required>
                        </div>                               
                    </div>
                    <div class="col-lg-4">
                        <div class="form-group">
                            <label for="exampleInputPassword1">Zila Parishad Wards To Be Created</label>
                            <span class="fa fa-asterisk"></span>
                            <input type="text" name="zp_ward" id="zp_ward" class="form-control" maxlength="2" required onkeypress='return event.charCode >= 48 && event.charCode <= 57'>
                        </div>                               
                    </div>  
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


