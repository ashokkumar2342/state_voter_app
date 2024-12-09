<div class="col-lg-12">
    <div class="card">
        <div class="card-header ui-sortable-handle" style="background: rgb(51, 102, 204);color: #fff;">
            <h3 class="card-title">
                &nbsp;
            </h3>
            <div class="card-tools">
            <ul class="nav nav-pills ml-auto">
                <li class="nav-item">
                    <button type="button" class="btn btn-sm btn-warning" select2="true" onclick="callPopupLarge(this,'{{ route('admin.Master.change.voter.ward.with.booth.report') }}'+'?village_id='+$('#village_select_box').val())">Report</button>
                </li>
            </ul>
        </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-lg-6 form-group"> 
                    <label>Data List</label>
                    <span class="fa fa-asterisk"></span>
                    <select name="data_list" id="data_list" class="form-control select2" data-table-new-without-pagination="ajax_data_table" onchange="callAjax(this,'{{ route('admin.claim.obj.ac.part.srno.changeBoothTable') }}'+'?data_list_id='+$('#data_list').val()+'&part_id='+$('#assembly_part_select_box').val()+'&block_id='+$('#block_select_box').val(),'result_table');">
                        <option selected disabled>Select Data List</option>
                        @foreach ($importTypes as $importType)
                            <option value="{{ Crypt::encrypt($importType->id) }}">{{ $importType->description }}</option> 
                        @endforeach 
                    </select> 
                </div>
                <div class="col-lg-6 form-group"> 
                    <label>Assembly--Part</label>
                    <span class="fa fa-asterisk"></span>
                    <select name="assembly_part" id="assembly_part_select_box" class="form-control select2" data-table-new-without-pagination="ajax_data_table" onchange="callAjax(this,'{{ route('admin.claim.obj.ac.part.srno.changeBoothTable') }}'+'?data_list_id='+$('#data_list').val()+'&part_id='+$('#assembly_part_select_box').val()+'&block_id='+$('#block_select_box').val(),'result_table');">
                        <option selected disabled>Select Assembly--Part</option>
                        @foreach ($assemblyParts as $assemblyPart)
                            <option value="{{ Crypt::encrypt($assemblyPart->id) }}">{{ $assemblyPart->code or '' }}--{{ $assemblyPart->part_no }}</option> 
                        @endforeach 
                    </select> 
                </div>
                <div class="col-lg-6 form-group">
                    <label>From Sr. No.</label>
                    <span class="fa fa-asterisk"></span>
                    <input type="text" name="from_sr_no" id="from_sr_no" class="form-control" maxlength="5" required onkeypress='return event.charCode >= 48 && event.charCode <= 57'> 
                </div>
                <div class="col-lg-6 form-group">
                    <label>To Sr. No. </label>
                    <input type="text" name="to_sr_no" id="to_sr_no" class="form-control" maxlength="5" onkeypress='return event.charCode >= 48 && event.charCode <= 57'> 
                </div>
                <div class="col-lg-6 form-group">
                    <label>Ward</label>
                    <span class="fa fa-asterisk"></span>
                    <select name="to_ward" id="to_ward_select_box" class="form-control select2" onchange="callAjax(this,'{{ route('admin.Master.WardWiseBooth') }}'+'?ward_id='+this.value+'&village_id='+$('#village_select_box').val(),'to_booth_select_box')">
                        <option selected disabled>Select Ward</option> 
                        @foreach ($WardVillages as $WardVillage)
                            @if ($WardVillage->lock==1)
                            @else  
                                <option value="{{ Crypt::encrypt($WardVillage->id) }}">{{ $WardVillage->ward_no }}</option> 
                            @endif
                        @endforeach 
                    </select> 
                </div>
                <div class="col-lg-6">
                    <label>Booth No.</label>
                    <span class="fa fa-asterisk"></span>
                    <select name="to_booth" class="form-control select2"  id="to_booth_select_box">
                        <option selected disabled>Select Booth No.</option> 
                    </select> 
                </div>
                <div class="col-lg-12 form-group"> 
                    <input type="submit" class="btn btn-success form-control" onclick="$('#from_sr_no').focus();"> 
                </div>
            </div>
        </div>
    </div>
</div>