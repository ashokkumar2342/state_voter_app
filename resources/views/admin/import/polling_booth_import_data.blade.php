<table class="table">
	<thead>
	   <tr> 
	       <th>State Code</th>
	       <th>District Code</th>
	       <th>Block Code</th>
	       <th>Panchayat Code</th>
	       <th>Polling Booth No.</th>
	       <th>Name (E)</th>
	       <th>Name (H)</th>
	       <th>Aux</th>
	       <th>Remarks</th>
	   </tr>
	</thead>
	<tbody>
	@foreach ($SaveResult as $importStatus) 
	   <tr style="{{ $importStatus->save_status==0?'background-color: #f35d6b':'#35cc78' }}">
	       <td>{{ $importStatus->scode }}</td>
	       <td>{{ $importStatus->dcode }}</td>
	       <td>{{ $importStatus->bcode }}</td>
	       <td>{{ $importStatus->vcode }}</td>
	       <td>{{ $importStatus->booth_no }}</td>
	       <td>{{ $importStatus->name_e }}</td>
	       <td>{{ $importStatus->name_l }}</td>
	       <td>{{ $importStatus->is_aux }}</td>
	       <td>{{ $importStatus->save_remarks }}</td>
	        
	   </tr>
	@endforeach
	</tbody>
</table>