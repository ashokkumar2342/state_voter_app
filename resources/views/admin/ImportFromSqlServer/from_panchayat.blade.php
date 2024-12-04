<option selected disabled value = "0">Select Block / MC's</option>
@foreach ($from_panchayat as $panchayat)
<option value="{{ $panchayat->PCODE }}">{{ $panchayat->PCODE }}--{{ $panchayat->name }}</option>  
@endforeach