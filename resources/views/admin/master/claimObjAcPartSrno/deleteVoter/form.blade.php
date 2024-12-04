<div class="col-lg-12"> 
  <div class="card card-danger"> 
    <div class="card-body">
      <div class="row"> 

        <div class="col-lg-3 form-group">
          <label>Data List</label>
          <select name="data_list" id="data_list" class="form-control" data-table="result_datatable" onchange="callAjax(this,'{{ route('admin.claim.obj.ac.part.srno.deleteVoterFormTable') }}'+'?data_list_id='+this.value+'&part_id='+$('#assembly_part_select_box').val()+'&block_id='+$('#block_select_box').val(),'result_table');" required>
            <option selected disabled>Select Data List</option>
            @foreach ($importTypes as $importType)
            <option value="{{ $importType->id }}">{{ $importType->description }}</option> 
            @endforeach 
          </select> 
        </div>

        <div class="col-lg-3 form-group">
          <label>Assembly--Part</label>
          <select name="assembly_part" id="assembly_part_select_box" class="form-control select2" data-table="result_datatable" onchange="callAjax(this,'{{ route('admin.claim.obj.ac.part.srno.deleteVoterFormTable') }}'+'?data_list_id='+$('#data_list').val()+'&part_id='+this.value+'&block_id='+$('#block_select_box').val(),'result_table');" required>
            <option selected disabled>Select Assembly--Part</option>
            @foreach ($assemblyParts as $assemblyPart)
            <option value="{{ $assemblyPart->id }}">{{ $assemblyPart->code or '' }}--{{ $assemblyPart->part_no }}</option> 
            @endforeach                        
          </select> 
        </div>
        
        <div class="col-lg-3 form-group">
          <label>From Sr. No.</label>
          <input type="text" name="from_sr_no" id="from_sr_no" class="form-control" maxlength="5" required onkeypress='return event.charCode >= 48 && event.charCode <= 57'> 
        </div>
        <div class="col-lg-3 form-group">
          <label>To Sr. No.</label>
          <input type="text" name="to_sr_no" id="to_sr_no" class="form-control" maxlength="5" onkeypress='return event.charCode >= 48 && event.charCode <= 57'> 
        </div>
        @if ($refreshdata == 0)
           <div class="col-lg-4 form-group" style="margin-top: 30px"> 
            <button type="button" class="btn btn-default form-control" onclick="callAjax(this,'{{ route('admin.claim.obj.ac.part.srno.deleteVoterFormTable') }}'+'?data_list_id='+$('#data_list').val()+'&part_id='+$('#assembly_part_select_box').val()+'&block_id='+$('#block_select_box').val(),'result_table');" style="background-color:#c2cad2"><i class="fa fa-refresh"></i> Refresh</button>
           </div>
           
         @endif
        <div class="col-lg-4 form-group" style="margin-top: 30px"> 
          <input type="submit" value="Delete" class="form-control btn-danger"> 
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
