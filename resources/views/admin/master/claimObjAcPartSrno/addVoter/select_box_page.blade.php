<div class="col-lg-12"> 
  <div class="card card-danger"> 
    <div class="card-body">
      <div class="row">
        <div class="col-lg-4 form-group">
          <label>Assembly--Part</label>
          <select name="assembly_part" id="assembly_part_select_box" class="form-control select2" data-table="result_datatable" onchange="callAjax(this,'{{ route('admin.claim.obj.ac.part.srno.addvoterWardTable') }}'+'?part_id='+this.value+'&block_id='+$('#block_select_box').val(),'result_table');" required>
            <option selected disabled>Select Assembly--Part</option>
            @foreach ($assemblyParts as $assemblyPart)
            <option value="{{ $assemblyPart->id }}">{{ $assemblyPart->code or '' }}--{{ $assemblyPart->part_no }}</option> 
            @endforeach                        
          </select> 
        </div>
        <div class="col-lg-4 form-group">
          <label>Epic No.</label>
          <input type="text" name="epic_no" id="epic_no" class="form-control" maxlength="25" required> 
        </div>
        <div class="col-lg-4 form-group">
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
           <div class="col-lg-4 form-group"  style="margin-top: 30px"> 
            <button type="button" class="btn btn-default form-control" onclick="callAjax(this,'{{ route('admin.claim.obj.ac.part.srno.addvoterWardTable') }}'+'?part_id='+$('#assembly_part_select_box').val()+'&block_id='+$('#block_select_box').val(),'result_table');" style="background-color:#c2cad2"><i class="fa fa-refresh"></i> Refresh</button>
           </div>
           
        @endif
        <div class="col-lg-4 form-group" style="margin-top: 30px"> 
          <input type="submit" value="Submit" class="form-control btn-success"> 
        </div>
        <div class="col-lg-4 form-group" style="margin-top: 30px"> 
          <a href="#" class="btn btn-warning form-control" onclick="callPopupLarge(this,'{{ route('admin.Master.change.voter.ward.with.acpart.report') }}'+'?village_id='+$('#village_select_box').val())">Report</a>
        </div>  
      </div> 
    </div>
  </div>
  <div id="result_table">
    
  </div> 
</div>
