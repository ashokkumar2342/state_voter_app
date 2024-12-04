<option selected disabled>Select Booth No.</option> 
@foreach ($booths as $booth)
<option value="{{ $booth->id }}">{{ $booth->booth_no }}-{{ $booth->name_e }}</option> 
@endforeach