<option selected disabled>Select P.S Ward</option> 
@foreach ($psWardsno as $psWardsn)
<option value="{{ $psWardsn->id }}">{{ $psWardsn->ward_no }}-{{ $psWardsn->name_e }}</option> 
@endforeach