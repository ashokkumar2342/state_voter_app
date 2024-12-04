<table class="table table-striped table-bordered">
	<thead>
		<tr>
			
			<th>Sr. No.</th>
			<th>Voter Card No.</th>
			<th>Name</th>
			<th>Village Name</th>
			<th>Ward No.</th>
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
			<td>
			@if ($result->status==1)
				<a  class="btn btn-danger btn-xs" select-triger="data_list" success-popup="true" onclick="callAjax(this,'{{ route('admin.new.voter.ward.wise.delete',[$result->id, $result->ward_id]) }}')" style="color: #fff">Remove</a>
			@endif
			
			</td>
		</tr> 
		@endforeach
	</tbody>
</table>