<option selected disabled>Select Booth</option>
@foreach ($booths as $booth)
 	<option value="{{ $booth->id }}">{{ $booth->booth_no }}{{ $booth->booth_no_c }}-{{ $booth->name_e}}</option> 
 @endforeach 