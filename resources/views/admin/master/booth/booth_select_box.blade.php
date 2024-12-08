<option selected disabled value="{{ Crypt::encrypt(0) }}">Select Booth</option>
@foreach ($booths as $booth)
 	<option value="{{ Crypt::encrypt($booth->id) }}">{{ $booth->booth_no }}{{ $booth->booth_no_c }}-{{ $booth->name_e}}</option> 
 @endforeach 