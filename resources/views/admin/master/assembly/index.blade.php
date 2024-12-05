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
            <form action="{{ route('admin.Master.Assembly.store', Crypt::encrypt(0)) }}" method="post" class="add_form" select-triger="district_select_box">
            {{ csrf_field() }} 
                <div class="form-group">
                    <label for="exampleInputEmail1">District</label>
                    <span class="fa fa-asterisk"></span>
                    <select name="district" class="form-control select2" id="district_select_box" data-table-new-without-pagination="ajax_data_table" onchange="callAjax(this,'{{ route('admin.Master.AssemblyTable') }}', 'result_div_id')">
                        <option selected disabled>Select District</option>
                        @foreach ($rs_district as $rs_val)
                            <option value="{{ Crypt::encrypt($rs_val->opt_id) }}">{{ $rs_val->opt_text }}</option>  
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="exampleInputEmail1">Assembly Code</label>
                    <span class="fa fa-asterisk"></span>
                    <input type="text" name="code" class="form-control" placeholder="Enter Code" maxlength="5" required>
                </div>
                <div class="form-group">
                    <label for="exampleInputPassword1">Assembly Name (English)</label>
                    <span class="fa fa-asterisk"></span>
                    <input type="text" name="name_english" class="form-control" placeholder="Enter Name (In English)" maxlength="50" required>
                </div>
                <div class="form-group">
                    <label for="exampleInputPassword1">Assembly Name (Hindi)</label>
                    <span class="fa fa-asterisk"></span>
                    <input type="text" name="name_local_language" class="form-control" placeholder="Enter Name (In Hindi)" maxlength="100" required>
                </div>
                <div class="form-group">
                    <label for="exampleInputEmail1">Parts To Be Created </label>
                    <span class="fa fa-asterisk"></span>
                    <input type="text" name="part_no" class="form-control" placeholder="Total Parts" maxlength="3" onkeypress='return event.charCode >= 48 && event.charCode <= 57'>
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


