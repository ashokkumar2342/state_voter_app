<table class="table table-striped table-bordered">
	<thead>
		<tr>
			<th>Assembly Part</th>
			<th>Sr. No.</th>
			<th>Name</th>
			<th>Father Name</th>
			<th>Action</th>
		</tr>
	</thead>
	<tbody>
		@foreach ($results as $result)
		<tr>
			<td>{{ $result->code}}-{{ $result->part_no}}</td>
			<td>{{ $result->sr_no }}</td>
			<td>{{ $result->name_e }}</td>
			<td>{{ $result->father_name_e }}</td>
			<td>
				<a href="#" class="btn btn-info btn-xs" select-triger="from_ward_select_box" success-popup="true" onclick="callAjax(this,'{{ route('admin.Master.add.voter.with.ward.delete',[$result->id, $result->ward_id]) }}')">Delete</a>
			</td>
		</tr> 
		@endforeach
	</tbody>
</table>