<table class="table table-striped table-bordered">
	<thead>
		<tr>
			<th>Print Sr. No.</th>
			<th>Name</th>
			<th>Father Name</th>
			<th>Ward No. - Booth No.</th>
			<th>Action</th>
		</tr>
	</thead>
	<tbody>
		@foreach ($results as $result)
		<tr>
			<td>{{ $result->print_sr_no}}</td>
			<td>{{ $result->name_e }}</td>
			<td>{{ $result->father_name_e }}</td>
			<td>{{ $result->ward_booth }}</td>
			<td>
				<a href="#" class="btn btn-info btn-xs" @if ($refreshdata == 1) select-triger="from_ward_select_box" @endif success-popup="true" onclick="callAjax(this,'{{ route('admin.Master.change.voter.with.ward.restore',[$result->id, $result->ward_id]) }}')">Restore</a>
			</td>
		</tr> 
		@endforeach
	</tbody>
</table>