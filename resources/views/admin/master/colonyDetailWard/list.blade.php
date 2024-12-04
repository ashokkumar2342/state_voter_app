<table class="table table-striped table-bordered">
<thead>
<tr>
<th>Ward No.</th> 
<th>Colony Detail</th> 
<th>Colony Detail</th> 
<th>Action</th> 
</tr>
</thead>
<tbody>
@foreach ($colony_details_wards as $colony_details_ward)
<tr>
	<td>{{ $colony_details_ward->ward_no}}</td>
	<td>{{ $colony_details_ward->colony_detail}}</td>
	<td> 
	<input type="text" name="colony_detail" maxlength="1000" id="colony_detail_{{ $colony_details_ward->id}}" value="{{ $colony_details_ward->colony_detail}}">
	<input type="hidden" name="ward_id"  id="ward_id_{{ $colony_details_ward->id}}" value="{{ $colony_details_ward->id}}">
	</td>
	<td>
		<a class="btn btn-info" success-popup="true" select-triger="village_select_box" onclick="callAjax(this,'{{ route('admin.master.update.ward.colonydetail') }}'+'?colony_detail='+$('#colony_detail_{{ $colony_details_ward->id}}').val()+'&ward_id='+$('#ward_id_{{ $colony_details_ward->id}}').val())">Update</a>
	</td>
</tr> 
@endforeach
</tbody>
</table>


