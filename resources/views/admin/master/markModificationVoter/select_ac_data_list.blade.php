<div class="col-lg-12"> 
  <div class="card card-danger"> 
    <div class="card-body">
      <div class="row"> 
        <div class="col-lg-6 form-group">
        <label>Assembly--Part</label>
        <select name="assembly_part" id="assembly_part_select_box" class="form-control select2" required onchange="disabledataList()">
          <option selected disabled>Select Assembly--Part</option>
          @foreach ($assemblyParts as $assemblyPart)
          <option value="{{ $assemblyPart->id }}">{{ $assemblyPart->code or '' }}--{{ $assemblyPart->part_no }}</option> 
          @endforeach 
        </select>
      </div>
      <div class="col-lg-6 form-group">
        <label>Data List</label>
        <select name="data_list" id="data_list" disabled class="form-control" required onchange="callAjax(this,'{{ route('admin.mark.modification.voter.table') }}'+'?part_id='+$('#assembly_part_select_box').val()+'&village_id='+$('#village_select_box').val(),'result_table')">
          <option selected disabled>Select Data List</option>
          @foreach ($import_type as $import_type)
          <option value="{{ $import_type->id }}">{{ $import_type->description}}</option> 
          @endforeach 
        </select> 
      </div>
      <div class="col-lg-12 form-group">
        <a class="btn btn-warning form-control" onclick="callPopupLarge(this,'{{ route('admin.mark.modification.voter.report') }}'+'?village_id='+$('#village_select_box').val()+'&assembly_part='+$('#assembly_part_select_box').val()+'&data_list='+$('#data_list').val())">Report</a> 
      </div>  
      </div> 
    </div>
  </div>
  <div id="result_table">
    
  </div> 
</div>
