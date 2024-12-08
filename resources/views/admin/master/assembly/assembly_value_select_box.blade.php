<option selected disabled value="{{ Crypt::encrypt(0) }}">Select Assembly</option>
@foreach ($assemblys as $assembly)
<option value="{{ Crypt::encrypt($assembly->id) }}">{{ $assembly->code }}--{{ $assembly->name_e }}</option>	 
@endforeach