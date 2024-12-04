	 	 
@foreach ($partnos as $partno) 
<tr>
<td>
<div class="icheck-primary d-inline">
<input type="checkbox" name="part_no[]" value="{{ $partno->part_no }}" id="{{ $partno->part_no }}{{ $partno->assembly_id }}"  class="checkbox">
<label for="{{ $partno->part_no }}{{ $partno->assembly_id }}" class="checkbox"></label>
</div> 
</td> 
<td>{{ $partno->part_no}}</td>
<td>{{ $partno->rtotal }}</td> 
<td>
	@if ($partno->rtotal!=0) 
	 <a href="{{ route('admin.database.conection.processDelete',[$partno->assembly_id,$partno->id]) }}" title="Delete Records" class="btn btn-xs btn-danger"><i class="fa fa-trash"></i></a>
	@endif
</td> 
</tr> 
@endforeach 
 