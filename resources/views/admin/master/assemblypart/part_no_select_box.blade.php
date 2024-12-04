<option selected disabled>Select Assembly Booth</option> 
@foreach ($Parts as $Part)
 
<option value="{{ $Part->id }}">{{ $Part->part_no }}</option>
 
@endforeach 