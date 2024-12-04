<option selected disabled>select P.S. Ward</option>
@foreach ($pswards as $psward)
 <option value="{{ $psward->id }}">{{ $psward->ward_no }}</option>	 
@endforeach