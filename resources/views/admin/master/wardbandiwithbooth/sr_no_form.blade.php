<div class="card card-info">
  <div class="card-header">
     <h3 class="card-title">Total Mapped : {{ $total_mapped[0]->total_mapped }}</h3>
    </div> 
    <div class="card-body" >
    <div class="row"> 
       <div class="col-lg-6 form-group">
       	<label>From Sr.No.</label>
       	<input type="text" name="from_sr_no" class="form-control" required maxlength="4" onkeypress='return event.charCode >= 48 && event.charCode <= 57'> 
        </div>
        <div class="col-lg-6 form-group">
       	<label>To Sr.No.</label>
       	<input type="text" name="to_sr_no" class="form-control" maxlength="4" onkeypress='return event.charCode >= 48 && event.charCode <= 57'> 
        </div>
    </div>    
        <div class="row"> 
          <div class="col-lg-3 form-group">
	       	<div class="icheck-primary d-inline">
	          <input type="checkbox" value="1" name="forcefully" id="todoCheck1" >
	          <label for="todoCheck1">Forcefully Move</label>
	        </div>
         </div>
         @if ($refreshdata == 0)
           <div class="col-lg-3 form-group"> 
            <button type="button" class="btn btn-default form-control" onclick="callAjax(this,'{{ route('admin.Master.WardBandiFilterAssemblyPart') }}'+'?assembly_part='+$('#assembly_part_select_box').val(),'voter_list')" style="background-color:#c2cad2"><i class="fa fa-refresh"></i> Refresh</button>
           </div>
           
         @endif
         
         <div class="col-lg-3 form-group"> 
       	 <input type="submit" class="btn btn-success form-control"> 
         </div>
         <div class="col-lg-3 form-group"> 
         <button type="button" class="btn btn-primary form-control" onclick="callPopupLarge(this,'{{ route('admin.Master.WardBandiReport') }}'+'?village='+$('#village_select_box').val()+'&assembly_part='+$('#assembly_part_select_box').val()+'&ward='+$('#ward_select_box').val())">Report</button> 
         </div>
        </div>    
    </div>
  </div>
</div>
