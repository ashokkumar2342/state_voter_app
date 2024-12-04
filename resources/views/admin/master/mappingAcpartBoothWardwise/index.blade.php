@extends('admin.layout.base')
@section('body')
</style>
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h3>Mapping (Ac Part No. Booth Ward Wise)</h3>
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
                    <form action="{{ route('admin.Master.mapping.acpart.booth.wardwiseStore') }}" method="post" class="add_form" no-reset="true">
                        {{ csrf_field() }} 
                        <div class="row"> 
                            <div class="col-lg-4 form-group">
                                <label for="exampleInputEmail1">State</label>
                                <span class="fa fa-asterisk"></span>
                                <select name="states" id="state_id" class="form-control select2" onchange="callAjax(this,'{{ route('admin.Master.stateWiseDistrict') }}','district_select_box')" required>
                                    <option selected disabled>Select State</option>
                                    @foreach ($States as $State)
                                    <option value="{{ $State->id }}">{{ $State->code }}--{{ $State->name_e }}</option>  
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-4 form-group">
                                <label for="exampleInputEmail1">District</label>
                                <span class="fa fa-asterisk"></span>
                                <select name="district" class="form-control select2" id="district_select_box" onchange="callAjax(this,'{{ route('admin.Master.DistrictWiseBlock') }}','block_select_box')" required>
                                    <option selected disabled>Select District</option>
                                </select>
                            </div>
                            <div class="col-lg-4 form-group">
                                <label for="exampleInputEmail1">Block / MC's</label>
                                <span class="fa fa-asterisk"></span>
                                <select name="block" class="form-control select2" id="block_select_box" onchange="callAjax(this,'{{ route('admin.Master.BlockWiseVillage') }}'+'?id='+this.value+'&district_id='+$('#district_select_box').val(),'village_select_box')" required>
                                    <option selected disabled>Select Block / MC's</option> 
                                </select>
                            </div>
                            <div class="col-lg-6 form-group">
                                <label for="exampleInputEmail1">Panchayat / MC's</label>
                                <span class="fa fa-asterisk"></span>
                                <select name="village" class="form-control select2" id="village_select_box" select2="true" data-table="ward_datatable" onchange="callAjax(this,'{{ route('admin.voter.VillageWiseWard') }}','ward_select_box')" required>
                                    <option selected disabled>Select Panchayat / MC's</option>

                                </select>
                            </div>
                            <div class="col-lg-6 form-group">
                                <label for="exampleInputEmail1">Ward</label>
                                <span class="fa fa-asterisk"></span>
                                <select name="ward" class="form-control" id="ward_select_box" onchange="callAjax(this,'{{ route('admin.Master.mapping.acpart.booth.wardwisetable') }}'+'?ward_id='+this.value+'&village_id='+$('#village_select_box').val(),'booth_select_box')" required>
                                    <option selected disabled>Select Ward</option> 
                                </select>
                            </div>
                            <div class="col-lg-12" id="booth_select_box">
                                 
                             </div>
                             <div class="col-lg-12 form-group">
                                <input type="submit" class="btn btn-primary form-control">
                                  
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
<script type="text/javascript">
    $('#ddd').DataTable();
</script> 

