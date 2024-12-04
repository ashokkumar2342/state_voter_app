<option selected disabled>Select Assembly--Part</option> 
@foreach ($assemblyParts as $val_rec)
 
<option value="{{ $val_rec->id }}">{{ $val_rec->code }} -- {{ $val_rec->part_no }}</option>
 
@endforeach 