<table class="table table-striped table-bordered"  id="result_datatable">
	<thead>
		<tr>
			<th>Sr. No.</th>
			<th>Voter Card No.</th>
			<th>Name</th>
			<th>Father Name</th>
			<th>Deleted From Ward No.</th>
			<th>Ward No.</th>
			<th>Action</th>
		</tr>
	</thead>
	<tbody>
		@foreach ($results as $result)
		<tr>
			<td>{{ $result->sr_no}}</td>
			<td>{{ $result->voter_card_no}}</td>
			<td>{{ $result->name_e }}</td>
			<td>{{ $result->father_name_e }}</td>
			<td>{{ $result->from_vil_ward }}</td>
			<td>{{ $result->vil_ward }}</td>
			<td>
				<a href="#" class="btn btn-info btn-xs" @if ($refreshdata == 1) select-triger="assembly_part_select_box"  @endif success-popup="true" onclick="callAjax(this,'{{ route('admin.Master.change.voter.with.ward.restore',[$result->id, $result->ward_id]) }}')">Restore</a>
			</td>
		</tr> 
		@endforeach
	</tbody>
</table>