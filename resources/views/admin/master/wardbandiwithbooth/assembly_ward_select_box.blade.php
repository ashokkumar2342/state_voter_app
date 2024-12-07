<div class="col-lg-6">
    <div class="card">
        <div class="card-header ui-sortable-handle" style="background: rgb(51, 102, 204);color: #fff;">
            <h3 class="card-title">
                &nbsp;
            </h3>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-lg-12 form-group">   
                    <label>Data List</label>
                    <span class="fa fa-asterisk"></span>
                    <select name="data_list" id="data_list" class="form-control select2" data-table-new-without-pagination="ajax_data_table" onchange="callAjax(this,'{{ route('admin.Master.AssemblywisevoterMapped') }}'+'?data_list_id='+this.value+'&part_id='+$('#assembly_part_select_box').val(),'result_div_id');disablewardNo()" required>
                        <option selected disabled>Select Data List</option>
                        @foreach ($rs_dataList as $dataList)
                        <option value="{{ Crypt::encrypt($dataList->id) }}">{{ $dataList->description or '' }}</option> 
                        @endforeach 
                    </select> 
                </div>
                <div class="col-lg-12 form-group">   
                    <label>Assembly-Part</label>
                    <span class="fa fa-asterisk"></span>
                    <select name="assembly_part" id="assembly_part_select_box" class="form-control select2" data-table-new-without-pagination="ajax_data_table" onchange="callAjax(this,'{{ route('admin.Master.AssemblywisevoterMapped') }}'+'?data_list_id='+$('#data_list').val()+'&part_id='+this.value,'result_div_id');disablewardNo()" required>
                        <option selected disabled>Select Assembly-Part</option>
                        @foreach ($assemblyParts as $assemblyPart)
                            <option value="{{ Crypt::encrypt($assemblyPart->id) }}">{{ $assemblyPart->code or '' }}-{{ $assemblyPart->part_no }}</option> 
                        @endforeach 
                    </select> 
                </div>
                <div class="col-lg-12 form-group">
                    <label>Ward</label>
                    <span class="fa fa-asterisk"></span>
                    <select name="ward" id="ward_select_box" class="form-control select2" onchange="callAjax(this,'{{ route('admin.Master.WardWiseBooth') }}'+'?ward_id='+this.value+'&village_id='+$('#village_select_box').val(),'booth_select_box')" required>
                        <option selected disabled>Select Ward</option> 
                        @foreach ($WardVillages as $WardVillage)
                            @if ($WardVillage->lock==1)
                            @else  
                                <option value="{{ Crypt::encrypt($WardVillage->id) }}">{{ $WardVillage->ward_no or '' }}</option> 
                            @endif
                        @endforeach 
                    </select> 
                </div>
                <div class="col-lg-12 form-group">
                    <label>Booth No.</label>
                    <span class="fa fa-asterisk"></span>
                    <select name="booth" class="form-control select2" onchange="callAjax(this,'{{ route('admin.Master.BoothWiseTotalMappedWard') }}','sr_no_form')" id="booth_select_box" required>
                        <option selected disabled>Select Booth No.</option> 
                    </select> 
                </div>
            </div>
        </div>
    </div>
</div>
<div class="col-lg-6">
    <div id="sr_no_form"></div>
</div>