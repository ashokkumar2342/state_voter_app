<option selected disabled>Select Block / MC's</option>
@foreach ($BlocksMcs as $BlocksMc)
<option value="{{ Crypt::encrypt($BlocksMc->id) }}">{{ $BlocksMc->code }}--{{ $BlocksMc->name_e }}</option>  
@endforeach