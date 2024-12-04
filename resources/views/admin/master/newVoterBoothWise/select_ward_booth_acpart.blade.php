<div class="col-lg-12"> 
  <div class="card card-danger"> 
    <div class="card-body">
      <div class="row"> 
        <div class="col-lg-3 form-group">
          <label>Ward</label>
          <select name="ward" id="from_ward_select_box" class="form-control select2">
            <option selected disabled>Select Ward</option> 
            @foreach ($WardVillages as $WardVillage)
            @if ($WardVillage->lock==1)
            @else  
            <option value="{{ $WardVillage->id }}">{{ $WardVillage->ward_no or '' }}</option> 
            @endif
            @endforeach 
          </select> 
        </div>
        <div class="col-lg-3 form-group">
          <label>Booth No.</label>
          <select name="booth" id="booth_select_box" class="form-control select2">
            <option selected disabled>Select Booth No.</option> 
            @foreach ($booths as $booth)
            <option value="{{ $booth->id }}">{{ $booth->booth_no }}-{{ $booth->name_e }}</option> 
            @endforeach 
          </select> 
        </div>
        <div class="col-lg-3 form-group">
        <label>Assembly--Part</label>
        <select name="assembly_part" id="assembly_part_select_box" class="form-control select2" required>
          <option selected disabled>Select Assembly--Part</option>
          @foreach ($assemblyParts as $assemblyPart)
          <option value="{{ $assemblyPart->id }}">{{ $assemblyPart->code or '' }}--{{ $assemblyPart->part_no }}</option> 
          @endforeach 
        </select>
      </div>
      <div class="col-lg-3 form-group">
        <label>Data List</label>
        <select name="data_list" id="data_list" class="form-control" required onchange="callAjax(this,'{{ route('admin.new.voter.booth.wise.table') }}'+'?part_id='+$('#assembly_part_select_box').val(),'result_table')">
          <option selected disabled>Select Data List</option>
          @foreach ($import_type as $import_type)
          <option value="{{ $import_type->id }}">{{ $import_type->description}}</option> 
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
          <input type="submit" value="Submit" class="form-control btn-success"> 
        </div>
        <div class="col-lg-6 form-group" style="margin-top: 30px"> 
          <a class="btn btn-warning form-control" onclick="callPopupLarge(this,'{{ route('admin.new.voter.booth.wise.report') }}'+'?village_id='+$('#village_select_box').val()+'&ward_id='+$('#from_ward_select_box').val()+'&booth='+$('#booth_select_box').val()+'&assembly_part='+$('#assembly_part_select_box').val()+'&data_list='+$('#data_list').val())">Report</a>
        </div>  
      </div> 
    </div>
  </div>
  <div id="result_table">
    
  </div> 
</div>
