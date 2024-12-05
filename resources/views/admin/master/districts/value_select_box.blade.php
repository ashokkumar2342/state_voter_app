<option selected disabled>Select District</option>
@foreach ($Districts as $District)
<option value="{{ Crypt::encrypt($District->id) }}">{{ $District->code }}--{{ $District->name_e }}</option>  
@endforeach