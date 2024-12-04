<option selected disabled>Select District</option>
@foreach ($Districts as $District)
<option value="{{ $District->id }}">{{ $District->code }}--{{ $District->name_e }}</option>  
@endforeach