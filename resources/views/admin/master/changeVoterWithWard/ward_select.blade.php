<div class="col-lg-12"> 
  <div class="card card-danger"> 
    <div class="card-body">
      <div class="row"> 
        <div class="col-lg-12 form-group">
          <label>From Ward</label>
          <select name="from_ward" id="from_ward_select_box" class="form-control select2" onchange="callAjax(this,'{{ route('admin.Master.change.voter.with.ward.table') }}'+'?ward_id='+$('#from_ward_select_box').val()+'&block_id='+$('#block_select_box').val(),'result_table');">
            <option selected disabled>Select Ward</option> 
            @foreach ($WardVillages as $WardVillage)
            @if ($WardVillage->lock==1)
            @else  
            <option value="{{ $WardVillage->id }}">{{ $WardVillage->ward_no or '' }}</option> 
            @endif
            @endforeach 
          </select> 
        </div>
        <div class="col-lg-6 form-group">
          <label>From Sr. No.</label>
          <input type="text" name="from_sr_no" id="from_sr_no" class="form-control" maxlength="5" required onkeypress='return event.charCode >= 48 && event.charCode <= 57'> 
        </div>
        <div class="col-lg-6 form-group">
          <label>To Sr. No.</label>
          <input type="text" name="to_sr_no" id="to_sr_no" class="form-control" maxlength="5" onkeypress='return event.charCode >= 48 && event.charCode <= 57'> 
        </div>
        <div class="col-lg-12 form-group">
          <label>To Ward</label>
          <select name="to_ward" id="to_ward_select_box" class="form-control select2">
            <option selected disabled>Select Ward</option> 
            @foreach ($WardVillages as $WardVillage)
            @if ($WardVillage->lock==1)
            @else  
            <option value="{{ $WardVillage->id }}">{{ $WardVillage->ward_no or '' }}</option> 
            @endif
            @endforeach 
          </select> 
        </div>
        @if ($refreshdata == 0)
           <div class="col-lg-4 form-group" style="margin-top: 30px"> 
            <button type="button" class="btn btn-default form-control" onclick="callAjax(this,'{{ route('admin.Master.change.voter.with.ward.table') }}'+'?ward_id='+$('#from_ward_select_box').val()+'&block_id='+$('#block_select_box').val(),'result_table');" style="background-color:#c2cad2"><i class="fa fa-refresh"></i> Refresh</button>
           </div>
           
        @endif
        <div class="col-lg-4 form-group" style="margin-top: 30px"> 
          <input type="submit" value="Submit" class="form-control btn-success"> 
        </div>
        <div class="col-lg-4 form-group" style="margin-top: 30px"> 
          <a href="#" class="btn btn-warning form-control" onclick="callPopupLarge(this,'{{ route('admin.Master.change.voter.with.ward.report') }}'+'?village_id='+$('#village_select_box').val())">Report</a>
        </div>  
      </div> 
    </div>
  </div>
  <div id="result_table">
    
  </div> 
</div>
