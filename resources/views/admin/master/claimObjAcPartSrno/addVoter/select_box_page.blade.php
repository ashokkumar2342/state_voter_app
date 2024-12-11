<div class="col-lg-12">
    <div class="card">
        <div class="card-header ui-sortable-handle" style="background: rgb(51, 102, 204);color: #fff;">
            <h3 class="card-title">
                &nbsp;
            </h3>
            <div class="card-tools">
            <ul class="nav nav-pills ml-auto">
                <li class="nav-item">
                    {{-- <button type="button" class="btn btn-sm btn-warning" select2="true" onclick="callPopupLarge(this,'{{ route('admin.Master.change.voter.ward.with.acpart.report') }}'+'?village_id='+$('#village_select_box').val())">Report</button> --}}
                </li>
            </ul>
        </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-lg-4 form-group">
                    <label>Assembly--Part</label>
                    <span class="fa fa-asterisk"></span>
                    <select name="assembly_part" id="assembly_part_select_box" class="form-control select2" data-table-new-without-pagination="ajax_data_table" onchange="callAjax(this,'{{ route('admin.claim.obj.ac.part.srno.addvoterWardTable') }}'+'?part_id='+this.value+'&block_id='+$('#block_select_box').val(),'result_table');" required>
                        <option selected disabled>Select Assembly--Part</option>
                        @foreach ($assemblyParts as $assemblyPart)
                            <option value="{{ Crypt::encrypt($assemblyPart->id) }}">{{ $assemblyPart->code or '' }}--{{ $assemblyPart->part_no }}</option> 
                        @endforeach                        
                    </select> 
                </div>
                <div class="col-lg-4 form-group">
                    <label>Epic No.</label>
                    <span class="fa fa-asterisk"></span>
                    <input type="text" name="epic_no" id="epic_no" class="form-control" maxlength="25" placeholder="Enter Epic No." required> 
                </div>
                <div class="col-lg-4 form-group">
                    <label>To Ward</label>
                    <span class="fa fa-asterisk"></span>
                    <select name="to_ward" id="to_ward_select_box" class="form-control select2">
                        <option selected disabled>Select Ward</option> 
                        @foreach ($WardVillages as $WardVillage)
                            @if ($WardVillage->lock==1)
                            @else  
                                <option value="{{ Crypt::encrypt($WardVillage->id) }}">{{ $WardVillage->ward_no}}</option> 
                            @endif
                        @endforeach 
                    </select> 
                </div>
                <div class="col-lg-12 form-group" style="margin-top: 10px;"> 
                    <input type="submit" class="btn btn-primary form-control" value="Submit"> 
                </div>
            </div>
        </div>
    </div>
</div>