<option selected disabled>Select Ward</option> 
@foreach ($WardVillages as $WardVillage)
 
<option value="{{ $WardVillage->id }}">{{ $WardVillage->ward_no }}</option>
 
@endforeach 