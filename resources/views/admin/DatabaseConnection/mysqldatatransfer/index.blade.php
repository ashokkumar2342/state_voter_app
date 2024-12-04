@extends('admin.layout.base')
@section('body')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h3>Mysql Data Transfer</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right"> 
                </ol>
            </div>
        </div> 
        <div class="card">
            <div class="card-body login-card-body">
            <form action="{{ route('admin.database.conection.MysqlDataTransferStore') }}" method="post" class="add_form" no-reset="true">
                <div class="row">
                    <div class="col-lg-3 form-group">
                        <label>District</label>
                        <select name="district_id" class="form-control" select-triger="block_select_box" onchange="callAjax(this,'{{ route('admin.Master.DistrictWiseBlock',2) }}','block_select_box')">
                            <option value="0">All</option>
                            @foreach ($Districts as $District)
                            <option value="{{ $District->id }}">{{ $District->code }}--{{ $District->name_e }}</option> 
                            @endforeach 
                        </select> 
                    </div>
                    <div class="col-lg-3 form-group">
                        <label>Block</label>
                        <select name="block_id" class="form-control" select-triger="village_select_box" id="block_select_box" onchange="callAjax(this,'{{ route('admin.Master.BlockWiseVillage') }}','village_select_box')">
                            <option value="0">All</option>
                             
                        </select> 
                    </div>
                    <div class="col-lg-3 form-group">
                        <label>Village</label>
                        <select name="village_id" class="form-control" id="village_select_box" onchange="callAjax(this,'{{ route('admin.voter.VillageWiseWardMultiple') }}','ward_select_box')">
                            <option value="0">All</option> 
                        </select> 
                    </div>
                    <div class="col-lg-3 form-group">
                        <label>Ward No.</label>
                        <select name="ward_id" class="form-control" id="ward_select_box">
                        <option value="0">All</option> 
                        </select> 
                    </div>
                    <div class="col-lg-12 form-group">
                      <input type="submit" class="btn btn-primary form-control" style="margin-top: 20px">  
                    </div> 
                 </div> 
             </form> 
            </div>
        </div>
    </div>
</section>
@endsection


