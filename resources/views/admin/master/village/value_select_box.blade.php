<option selected disabled value = "0">Select Panchayat / MC's</option>
@foreach ($Villages as $Village)
<option value="{{ $Village->id }}">{{ $Village->code }}--{{ $Village->name_e }} {{ $Village->is_locked==1?'(Locked)':'' }}</option>  
@endforeach