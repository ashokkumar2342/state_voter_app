<table class="table table-striped table-bordered">
<thead>
<tr>
<th>Assembly</th> 
<th>Part No.</th> 
<th>Total Vote</th> 
<th>Booth</th> 

</tr>
</thead>
<tbody>
@foreach ($mappingAcpartBoothWardwise as $mappingAcpartBoothWardwise)
<tr>
	<td>{{ $mappingAcpartBoothWardwise->code}}</td>
	<td>{{ $mappingAcpartBoothWardwise->part_no}}</td>
	<td>{{ $mappingAcpartBoothWardwise->total_vote}}</td>
	
	<td> 
		<select name="booth_id[{{$mappingAcpartBoothWardwise->acpartid}}]" class="form-control">
			<option selected disabled>Select Option</option>
			@foreach ($selectbooths as $selectbooth)
				<option value="{{$selectbooth->id}}">{{$selectbooth->id}}</option>
			@endforeach 
		</select> 
	</td>
	
</tr> 
@endforeach
</tbody>
</table>


