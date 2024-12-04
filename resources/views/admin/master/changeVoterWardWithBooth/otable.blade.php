<table class="table table-striped table-bordered">
	<thead>
		<tr>
			<th>Print Sr.no.</th>
			<th>Name</th>
			<th>Father Name</th>
			<th>Ward No.</th>
			<th>Action</th>
		</tr>
	</thead>
	<tbody>
		@foreach ($results as $result)
		<tr>
			<td>{{ $result->print_sr_no}}</td>
			<td>{{ $result->name_e }}</td>
			<td>{{ $result->father_name_e }}</td>
			<td>{{ $result->ward_no }}</td>
			<td>
				<a href="#" class="btn btn-info btn-xs" select-triger="from_booth_select_box" success-popup="true" onclick="callAjax(this,'{{ route('admin.Master.change.voter.with.ward.restore',$result->id) }}')">Restore</a>
			</td>
		</tr> 
		@endforeach
	</tbody>
</table>