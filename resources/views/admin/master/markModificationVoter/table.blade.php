<table class="table table-striped table-bordered">
	<thead>
		<tr>
			
			<th>Sr. No.</th>
			<th>Voter Card No.</th>
			<th>Name</th>
			<th>MC Name</th>
			<th>Ward No.</th>
			<th>Print Sr. No. In List</th>
			<th>Action</th>
		</tr>
	</thead>
	<tbody>
		@foreach ($results as $result)
		<tr>
			<td>{{ $result->sr_no}}</td>
			<td>{{ $result->voter_card_no }}</td>
			<td>{{ $result->name_l }}</td>
			<td>{{ $result->village_name }}</td>
			<td>{{ $result->ward_no }}</td>
			<td>{{ $result->print_sr_no }}</td>
			<td>
			@if ($result->ward_id == 0)
			 	<a class="btn btn-info btn-xs" onclick="callPopupLarge(this,'{{ route('admin.mark.modification.voter.update',[$result->id,$village_id]) }}')" style="color: #fff">Update</a>
			@else
				<a class="btn btn-danger btn-xs" success-popup="true" select-triger="data_list" onclick="callAjax(this,'{{ route('admin.mark.modification.voter.restore',[$result->id,$result->ward_id]) }}')" style="color: #fff">Restore</a>
			@endif 
			
			 
			
			</td>
		</tr> 
		@endforeach
	</tbody>
</table>