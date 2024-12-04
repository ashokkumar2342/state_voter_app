@extends('admin.layout.base')
@section('body')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h3>Polling Booths</h3>
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
                            <form action="{{ route('admin.Master.boothStore') }}" method="post" class="add_form" no-reset="true" reset-input-text="booth_no,booth_name_english,booth_name_local,booth_no_c" select-triger="village_select_box">
                            {{ csrf_field() }} 
                                    <div class="row"> 
                                    <div class="col-lg-3 form-group">
                                    <label for="exampleInputEmail1">State</label>
                                    <span class="fa fa-asterisk"></span>
                                    <select name="states" id="state_id" class="form-control select2" onchange="callAjax(this,'{{ route('admin.Master.stateWiseDistrict') }}','district_select_box')">
                                    <option selected disabled>Select State</option>
                                    @foreach ($States as $State)
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
                                        <select name="village" class="form-control select2" id="village_select_box" select2="true" data-table="ward_datatable" onchange="callAjax(this,'{{ route('admin.Master.boothTable') }}','ward_table')" required>
                                            <option selected disabled>Select Panchayat / MC's</option>
                                            
                                        </select>
                                    </div>
                                    <div class="col-lg-3 form-group">
                                        <label for="exampleInputEmail1">Polling Booth No.</label>
                                        <span class="fa fa-asterisk"></span>
                                        <input type="text" name="booth_no" id="booth_no" class="form-control" placeholder="" maxlength="5" required onkeypress='return event.charCode >= 48 && event.charCode <= 57'>
                                    </div>
                                    <div class="col-lg-3 form-group">
                                        <label for="exampleInputEmail1">Booth No. (Auxiliary)</label>
                                         
                                        <input type="text" name="booth_no_c" id="booth_no_c" class="form-control" placeholder="" maxlength="1">
                                    </div>
                                    <div class="col-lg-3 form-group">
                                        <label for="exampleInputEmail1">Booth Name (English) </label>
                                        <span class="fa fa-asterisk"></span>
                                        <input type="text" name="booth_name_english" id="booth_name_english" class="form-control" placeholder="" maxlength="100" required>
                                    </div>
                                    <div class="col-lg-3 form-group">
                                        <label for="exampleInputEmail1">Booth Name (Hindi)</label>
                                        <span class="fa fa-asterisk"></span>
                                        <input type="text" name="booth_name_local" id="booth_name_local" class="form-control" placeholder="" maxlength="100" required>
                                    </div>
                                    <div class="col-lg-12 form-group">
                                     <input type="submit" class="form-control btn btn-primary" style="margin-top: 30px">
                                    </div>
                            </form>
                        </div> 
                    <div class="col-lg-12" id="ward_table">
                        <div class="card card-primary table-responsive"> 
                             <table id="district_table" class="table table-striped table-hover control-label">
                                 <thead>
                                     <tr>
                                         <th class="text-nowrap">Polling Booth No.</th>
                                         <th class="text-nowrap">Booth Name (Eng.)</th>
                                         <th class="text-nowrap">Booth Name (Hin.)</th> 
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
    <script type="text/javascript">
        $('#ddd').DataTable();
    </script> 

