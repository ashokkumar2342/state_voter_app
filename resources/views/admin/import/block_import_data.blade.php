<table class="table">
	<thead>
	   <tr> 
	       <th>District Code</th>
	       <th>Block Code</th>
	       <th>Name (E)</th>
	       <th>Name (H)</th>
	       <th>Block Type</th>
	       <th>PS Wards</th>
	       <th>Remarks</th>
	   </tr>
	</thead>
	<tbody>
	@foreach ($SaveResult as $importStatus) 
	   <tr style="{{ $importStatus->save_status==0?'background-color: #f35d6b':'#35cc78' }}">
	       <td>{{ $importStatus->dcode }}</td>
	       <td>{{ $importStatus->bcode }}</td>
	       <td>{{ $importStatus->bname_e }}</td>
	       <td>{{ $importStatus->bname_l }}</td>
	       <td>{{ $importStatus->block_type }}</td>
	       <td>{{ $importStatus->total_wards }}</td>
	       <td>{{ $importStatus->save_remarks }}</td>
	        
	   </tr>
	@endforeach
	</tbody>
</table>