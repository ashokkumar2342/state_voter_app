<option value="0">All</option>
@foreach ($villages as $village)
<option value="{{ $village->id }}">{{ $village->code }}--{{ $village->name_e }}</option> 
@endforeach