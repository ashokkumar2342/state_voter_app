<option selected disabled>Select Part</option> 
@foreach ($Parts as $Part) 
<option value="{{ $Part->id }}">{{ $Part->part_no }}</option> 
@endforeach 