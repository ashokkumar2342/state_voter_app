<option selected disabled>Select Z.P Ward</option> 
@foreach ($zpWards as $zpWard)
<option value="{{ $zpWard->id }}">{{ $zpWard->ward_no }}-{{ $zpWard->name_e }}</option> 
@endforeach