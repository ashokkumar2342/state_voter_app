<option value="0">All</option>
@foreach ($wards as $ward)
<option value="{{ $ward->id }}">{{ $ward->ward_no }}</option> 
@endforeach