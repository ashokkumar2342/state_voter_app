<option selected disabled value = "0">Select Block / MC's</option>
@foreach ($BlocksMcs as $BlocksMc)
<option value="{{ $BlocksMc->id }}">{{ $BlocksMc->code }}--{{ $BlocksMc->name_e }}</option>  
@endforeach