 <div class="row"> 
 <div class="col-md-6"> 
 {{ Form::label('Districts','Districts',['class'=>' control-label']) }} <br>
<select class="form-control"   id="district_id"  multiselect-form="true" name="districts"  onchange="callAjax(this,'{{route('admin.Master.DistrictWiseBlockMulti')}}'+'?id='+this.value+'&district_id='+$('#district_id').val()+'&user_id='+$('#user_id').val(),'class_list')" > 
		<option value="" selected="" disabled="">Select Districts</option>		 
      @foreach ($Districts as $Districts)   
        <option value="{{ $Districts->id }}">{{ $Districts->name_l or '' }}</option> 
      @endforeach 
</select>
</div>
<div class="col-md-3" id="class_list"> 
 {{ Form::label('Districts','Block',['class'=>' control-label']) }} <br>
<select class="selectpicker multiselect" multiple data-live-search="true"  name="block[]"> 
           
 </select> 
</div>
<div class="col-lg-3">
<input type="submit" class="btn btn-primary form-control" value="Save" style="margin-top: 30px">	
</div>
<div class="col-lg-12" style="margin-top: 20px"> 
<table class="table  table-bordered table-striped" id="class_section_list">
	<thead>
		<tr>
			<th>District</th>
			<th>Block</th>
		</tr>
	</thead>
	<tbody>
		@foreach ($DistrictBlockAssigns as $DistrictBlockAssign)
					<tr>
						<td>{{ $DistrictBlockAssign->Districts->name_l or ''}}</td>
						<td>{{ $DistrictBlockAssign->Blocks->name_l or ''}}</td>
						 
					</tr> 
		@endforeach
	</tbody>
</table> 
 </div>

