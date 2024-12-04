<table class="table">
	<thead>
	   <tr> 
	       <th>State</th>
	       <th>District</th>
	       <th>Block</th>
	       <th>Panchayat</th>
	       <th>From Ward</th>
	       <th>From Booth</th>
	       <th>From Sr.No.</th>
	       <th>To Sr.No.</th>
	       <th>To Ward</th>
	       <th>To Booth</th>
	       <th>Remarks</th>
	   </tr>
	</thead>
	<tbody>
	@foreach ($SaveResult as $importStatus) 
	   <tr style="{{ $importStatus->status==1?'background-color: #f35d6b':'#35cc78' }}">
	       <td>{{ $importStatus->state_code }}</td>
	       <td>{{ $importStatus->district_code }}</td>
	       <td>{{ $importStatus->block_code }}</td>
	       <td>{{ $importStatus->village_code }}</td>
	       <td>{{ $importStatus->from_ward }}</td>
	       <td>{{ $importStatus->from_booth }}</td>
	       <td>{{ $importStatus->from_srno }}</td>
	       <td>{{ $importStatus->to_srno }}</td>
	       <td>{{ $importStatus->to_ward }}</td>
	       <td>{{ $importStatus->to_booth }}</td>
	       <td>{{ $importStatus->remarks }}</td>
	        
	   </tr>
	@endforeach
	</tbody>
</table>