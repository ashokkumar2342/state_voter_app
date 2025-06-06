<div class="col-lg-12"> 
  <div class="card card-danger"> 
    <div class="card-body">
      <div class="row"> 
        <div class="col-lg-4 form-group">
          <label>Ward</label>
          <select name="from_ward" id="from_ward_select_box" class="form-control select2" onchange="callAjax(this,'{{ route('admin.Master.add.voter.with.ward.table') }}'+'?ward_id='+this.value+'&booth_id=0','result_table')">
            <option selected disabled>Select Ward</option> 
            @foreach ($WardVillages as $WardVillage)
            @if ($WardVillage->lock==1)
            @else  
            <option value="{{ $WardVillage->id }}">{{ $WardVillage->ward_no or '' }}</option> 
            @endif
            @endforeach 
          </select> 
        </div>
        <div class="col-lg-4 form-group">
        <label>Assembly--Part</label>
        <select name="assembly_part" id="assembly_part_select_box" class="form-control select2" required>
          <option selected disabled>Select Assembly--Part</option>
          @foreach ($assemblyParts as $assemblyPart)
          <option value="{{ $assemblyPart->id }}">{{ $assemblyPart->code or '' }}--{{ $assemblyPart->part_no }}</option> 
          @endforeach 
        </select> 
      </div>
      <div class="col-lg-4 form-group">
        <label>Data List</label>
        <select name="data_list" id="data_list_select_box" class="form-control" required>
          <option selected disabled>Select Data List</option>
          @foreach ($importTypes as $importType)
          <option value="{{ $importType->id }}">{{ $importType->description }}</option> 
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
        <div class="col-lg-6 form-group" style="margin-top: 30px"> 
          <input type="submit" value="Add Voter" class="form-control btn-success"> 
        </div>
        <div class="col-lg-6 form-group" style="margin-top: 30px"> 
          <a href="#" class="btn btn-warning form-control" onclick="callPopupLarge(this,'{{ route('admin.Master.add.voter.with.ward.report') }}'+'?village_id='+$('#village_select_box').val())">Report</a>
        </div>  
      </div> 
    </div>
  </div>
  <div id="result_table">
    
  </div> 
</div>
