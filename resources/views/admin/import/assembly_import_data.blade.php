<table class="table">
	<thead>
	   <tr> 
	       <th>District Code</th>
	       <th>Assembly Code</th>
	       <th>Name (E)</th>
	       <th>Name (H)</th>
	       <th>Parts (Booths)</th>
	       <th>Remarks</th>
	   </tr>
	</thead>
	<tbody>
	@foreach ($SaveResult as $importStatus) 
	   <tr style="{{ $importStatus->save_status==0?'background-color: #f35d6b':'#35cc78' }}">
	       <td>{{ $importStatus->dcode }}</td>
	       <td>{{ $importStatus->acode }}</td>
	       <td>{{ $importStatus->aname_e }}</td>
	       <td>{{ $importStatus->aname_l }}</td>
	       <td>{{ $importStatus->total_parts }}</td>
	       <td>{{ $importStatus->save_remarks }}</td>
	        
	   </tr>
	@endforeach
	</tbody>
</table>