<table class="table table-striped table-bordered">
	<thead>
		<tr>
			
			<th>Ac - Part No.</th>
			<th>Sr. No.</th>
			<th>Name</th>
			<th>F/H Name</th>
			<th>Ward No.</th>
			<th>Print Sr. No.</th>
			<th>Description</th>
			<th>Status</th>
			
		</tr>
	</thead>
	<tbody>
		@foreach ($rs_result as $result)
		<tr>
			<td>{{ $result->code}} - {{ $result->part_no}}</td>
			<td>{{ $result->sr_no }}</td>
			<td>{{ $result->name_e }}</td>
			<td>{{ $result->father_name_e }}</td>
			<td>{{ $result->ward_no }}</td>
			<td>{{ $result->print_sr_no }}</td>
			<td>{{ $result->description }}</td>
			<td>{{ $result->voter_status }}</td>
			
		</tr> 
		@endforeach
	</tbody>
</table>