<table class="table">
	<thead>
	   <tr> 
	       <th>District Code</th>
	       <th>Block Code</th>
	       <th>Panchayat Code</th>
	       <th>Name (E)</th>
	       <th>Name (H)</th>
	       <th>Wards</th>
	       <th>Remarks</th>
	   </tr>
	</thead>
	<tbody>
	@foreach ($SaveResult as $importStatus) 
	   <tr style="{{ $importStatus->save_status==0?'background-color: #f35d6b':'#35cc78' }}">
	       <td>{{ $importStatus->dcode }}</td>
	       <td>{{ $importStatus->bcode }}</td>
	       <td>{{ $importStatus->vcode }}</td>
	       <td>{{ $importStatus->vname_e }}</td>
	       <td>{{ $importStatus->vname_l }}</td>
	       <td>{{ $importStatus->total_ward }}</td>
	       <td>{{ $importStatus->save_remarks }}</td>
	        
	   </tr>
	@endforeach
	</tbody>
</table>