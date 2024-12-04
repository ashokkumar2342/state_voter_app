<table class="table">
	<thead>
	   <tr> 
	       <th>User ID</th>
	       <th>Dist</th>
	       <th>Block</th>
	       <th>Village</th>
	       <th>Data List</th>
	       <th>AC No.</th>
	       <th>Part No.</th>
	       <th>From Sr. No.</th>
	       <th>To Sr. No.</th>
	       <th>Remarks</th>
	   </tr>
	</thead>
	<tbody>
	@foreach ($SaveResult as $importStatus) 
	   <tr>
	       <td>{{ $importStatus->user_id }}</td>
	       <td>{{ $importStatus->distcode }}</td>
	       <td>{{ $importStatus->block_code }}</td>
	       <td>{{ $importStatus->village_code }}</td>
	       <td>{{ $importStatus->data_list_id }}</td>
	       <td>{{ $importStatus->ac_no }}</td>
	       <td>{{ $importStatus->part_no }}</td>
	       <td>{{ $importStatus->from_srno }}</td>
	       <td>{{ $importStatus->to_srno }}</td>
	       <td>{{ $importStatus->remarks }}</td>
	        
	   </tr>
	@endforeach
	</tbody>
</table>