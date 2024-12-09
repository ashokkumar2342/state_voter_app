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
                    <label>Ward</label>
                    <span class="fa fa-asterisk"></span>
                    <select name="from_ward" id="from_ward_select_box" class="form-control select2" onchange="callAjax(this,'{{ route('admin.Master.WardWiseBooth') }}'+'?ward_id='+this.value+'&village_id='+$('#village_select_box').val(),'from_booth_select_box')">
                        <option selected disabled>Select Ward</option> 
                        @foreach ($WardVillages as $WardVillage)
                            @if ($WardVillage->lock==1)
                            @else  
                                <option value="{{ Crypt::encrypt($WardVillage->id) }}">{{ $WardVillage->ward_no}}</option> 
                            @endif
                        @endforeach 
                    </select> 
                </div>
                <div class="col-lg-6 form-group"> 
                    <label>Polling Booth</label>
                    <span class="fa fa-asterisk"></span>
                    <select name="from_booth" class="form-control select2"  id="from_booth_select_box" data-table-new-without-pagination="ajax_data_table" onchange="callAjax(this,'{{ route('admin.Master.change.voter.ward.with.booth.table') }}'+'?from_booth='+this.value+'&from_ward_id='+$('#from_ward_select_box').val(),'result_table')">
                    <option selected disabled>Select Polling Booth</option> 
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
                <div class="col-lg-12 form-group"> 
                    <input type="submit" class="btn btn-danger form-control" onclick="$('#from_sr_no').focus();" value="Delete"> 
                </div>
            </div>
        </div>
    </div>
</div>