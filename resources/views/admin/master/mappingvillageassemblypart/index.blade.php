@extends('admin.layout.base')
@section('body')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h3>Mapping (Panchayat / MC's - Assembly Part)</h3>
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
                        <div class="card card-primary"> 
                            <form action="{{ route('admin.Master.MappingVillageAssemblyPartStore') }}" method="post" class="add_form" content-refresh="district_table" no-reset="true" select-triger="village_select_box,assembly_select_box">
                                {{ csrf_field() }}
                                <div class="card-body">
                                    <div class="row"> 
                                    <div class="col-lg-3 form-group">
                                        <label for="exampleInputEmail1">State</label>
                                        <span class="fa fa-asterisk"></span>
                                        <select name="states" class="form-control" onchange="callAjax(this,'{{ route('admin.Master.stateWiseDistrict') }}','district_select_box')" required>
                                            <option selected disabled>Select State</option>
                                            @foreach ($States as $State)
                                            <option value="{{ $State->id }}">{{ $State->code }}--{{ $State->name_e }}</option>  
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-lg-3 form-group">
                                        <label for="exampleInputEmail1">District</label>
                                        <span class="fa fa-asterisk"></span>
                                        <select name="district" class="form-control" id="district_select_box" onchange="callAjax(this,'{{ route('admin.Master.DistrictWiseBlock') }}','block_select_box');callAjax(this,'{{ route('admin.Master.MappingVillageAssemblyPartFilter') }}'+'?district_id='+$('#district_select_box').val(),'value_div_id')" required>
                                            <option selected disabled>Select District</option>
                                        </select>
                                    </div>
                                    <div class="col-lg-3 form-group">
                                        <label for="exampleInputEmail1">Block / MC's</label>
                                        <span class="fa fa-asterisk"></span>
                                        <select name="block_mcs" class="form-control" id="block_select_box" onchange="callAjax(this,'{{ route('admin.Master.BlockWiseVillage') }}'+'?district_id='+$('#district_select_box').val(),'village_select_box')" required>
                                            <option selected disabled>Select Block / MC's</option>
                                             
                                        </select>
                                    </div>
                                    <div class="col-lg-3 form-group">
                                        <label for="exampleInputEmail1">Panchayat / MC's</label>
                                        <span class="fa fa-asterisk"></span>
                                        <select name="village" class="form-control" id="village_select_box" onchange="callAjax(this,'{{ route('admin.Master.MappingVillageAssemblyPartTable') }}'+'?district_id='+$('#district_select_box').val(),'value_table_div')" required>
                                            <option selected disabled>Select Panchayat / MC's</option>
                                            
                                        </select>
                                    </div>
                                </div>
                                <div class="row" id="value_div_id">
                                      
                                </div> 
                            </form>
                        </div> 
                    </div>
                     
                </div>
            </div> 
        </div> 
    </section>
    @endsection
    <script type="text/javascript">
        $('#ddd').DataTable();
    </script> 

