<table class="table table-striped table-bordered">
<thead>
<tr>
<th>Booth No.</th> 
<th>Last Voter Sr.No.</th> 
<th>Last Voter Sr.No.</th> 
<th>Action</th> 
</tr>
</thead>
<tbody>
@foreach ($last_srno_ward_booth as $last_srno_booth)
<tr>
	<td>{{ $last_srno_booth->booth_no}}</td>
	<td>{{ $last_srno_booth->last_srno}}</td>
	<td> 
	<input type="text" name="sr_no" maxlength="5" id="last_srno_{{ $last_srno_booth->id}}" onkeypress='return event.charCode >= 48 && event.charCode <= 57' value="{{ $last_srno_booth->last_srno}}">
	<input type="hidden" name="booth_id"  id="booth_id_{{ $last_srno_booth->id}}" value="{{ $last_srno_booth->id}}">
	</td>
	<td>
		<a class="btn btn-info" success-popup="true" select-triger="village_select_box" onclick="callAjax(this,'{{ route('admin.last.voter.srno.booth.update') }}'+'?sr_no='+$('#last_srno_{{ $last_srno_booth->id}}').val()+'&booth_id='+$('#booth_id_{{ $last_srno_booth->id}}').val())">Update</a>
	</td>
</tr> 
@endforeach
</tbody>
</table>


