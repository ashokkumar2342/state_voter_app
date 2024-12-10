<div class="card">
    <div class="card-header ui-sortable-handle" style="background: rgb(51, 102, 204);color: #fff;">
        <h3 class="card-title">
            Total Mapped : {{ $total_mapped[0]->total_mapped }}
        </h3>
        <div class="card-tools">
            <ul class="nav nav-pills ml-auto">
                <li class="nav-item">
                    <button type="button" class="btn btn-sm btn-warning" style="width: 150px;" onclick="callPopupLarge(this,'{{ route('admin.Master.WardBandiReport') }}'+'?village='+$('#village_select_box').val()+'&assembly_part='+$('#assembly_part_select_box').val()+'&ward='+$('#ward_select_box').val())">Report</button>
                </li>
            </ul>
        </div>
    </div>
    <div class="card-body">
        <div class="row"> 
            <div class="col-lg-12 form-group">
                <label>From Sr.No.</label>
                <span class="fa fa-asterisk"></span>
                <input type="text" name="from_sr_no" id="from_sr_no" class="form-control" maxlength="4" onkeypress='return event.charCode >= 48 && event.charCode <= 57' required> 
            </div>
            <div class="col-lg-12 form-group">
                <label>To Sr.No.</label>
                <input type="text" name="to_sr_no" id="to_sr_no" class="form-control" maxlength="4" onkeypress='return event.charCode >= 48 && event.charCode <= 57'> 
            </div>
            <div class="col-lg-12 form-group">
                <div class="icheck-primary d-inline">
                    <input type="checkbox" value="1" name="forcefully" id="todoCheck1" >
                    <label for="todoCheck1">Forcefully Move</label>
                </div>
            </div>
            <div class="col-lg-12 form-group"> 
                <input type="submit" class="btn btn-success form-control" onclick="setfocusonfromsrno();"> 
            </div>
        </div>
    </div>
</div>
