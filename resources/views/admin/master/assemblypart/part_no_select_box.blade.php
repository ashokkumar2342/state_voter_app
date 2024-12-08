<option selected disabled value="{{ Crypt::encrypt(0) }}">Select Assembly Booth</option> 
@foreach ($rs_parts as $rs_val) 
	<option value="{{ Crypt::encrypt($rs_val->id) }}">{{ $rs_val->part_no }}</option> 
@endforeach 