<table class="table">
	<thead>
	   <tr> 
	       <th>State Code</th>
	       <th>District Code</th>
	       <th>Name (E)</th>
	       <th>Name (H)</th>
	       <th>ZP Wards</th>
	       <th>Remarks</th>
	   </tr>
	</thead>
	<tbody>
	@foreach ($SaveResult as $importStatus) 
	   <tr style="{{ $importStatus->save_status==0?'background-color: #f35d6b':'#35cc78' }}">
	       <td>{{ $importStatus->scode }}</td>
	       <td>{{ $importStatus->dcode }}</td>
	       <td>{{ $importStatus->dname_e }}</td>
	       <td>{{ $importStatus->dname_l }}</td>
	       <td>{{ $importStatus->total_wards }}</td>
	       <td>{{ $importStatus->save_remarks }}</td>
	        
	   </tr>
	@endforeach
	</tbody>
</table>