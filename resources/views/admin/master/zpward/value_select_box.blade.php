<option selected disabled>select Z.P. Ward</option>
@foreach ($zpwards as $zpward)
 <option value="{{ $zpward->id }}">{{ $zpward->ward_no }}</option>	 
@endforeach