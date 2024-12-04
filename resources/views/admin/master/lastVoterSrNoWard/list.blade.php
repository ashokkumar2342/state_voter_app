<table class="table table-striped table-bordered">
<thead>
<tr>
<th>Ward No.</th> 
<th>Last Voter Sr.No.</th> 
<th>Last Voter Sr.No.</th> 
<th>Action</th> 
</tr>
</thead>
<tbody>
@foreach ($last_srno_wards as $last_srno_ward)
<tr>
	<td>{{ $last_srno_ward->ward_no}}</td>
	<td>{{ $last_srno_ward->last_srno}}</td>
	<td> 
	<input type="text" name="sr_no" maxlength="5" id="last_srno_{{ $last_srno_ward->id}}" onkeypress='return event.charCode >= 48 && event.charCode <= 57' value="{{ $last_srno_ward->last_srno}}">
	<input type="hidden" name="ward_id"  id="ward_id_{{ $last_srno_ward->id}}" value="{{ $last_srno_ward->id}}">
	</td>
	<td>
		<a class="btn btn-info" success-popup="true" select-triger="village_select_box" onclick="callAjax(this,'{{ route('admin.last.voter.srno.ward.update') }}'+'?sr_no='+$('#last_srno_{{ $last_srno_ward->id}}').val()+'&ward_id='+$('#ward_id_{{ $last_srno_ward->id}}').val())">Update</a>
	</td>
</tr> 
@endforeach
</tbody>
</table>


