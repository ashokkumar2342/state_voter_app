<table class="table">
	<thead>
	   <tr> 
	       <th>AC Code</th>
	       <th>Part No.</th>
	       <th>From Sr.No.</th>
	       <th>To Sr.No.</th>
	       <th>District Code</th>
	       <th>Block Code</th>
	       <th>Panchayat Code</th>
	       <th>Ward No.</th>
	       <th>Booth No.</th>
	       <th>Remarks</th>
	   </tr>
	</thead>
	<tbody>
	@foreach ($SaveResult as $importStatus) 
	   <tr style="{{ $importStatus->save_status==0?'background-color: #f35d6b':'#35cc78' }}">
	       <td>{{ $importStatus->ac_code }}</td>
	       <td>{{ $importStatus->part_no }}</td>
	       <td>{{ $importStatus->from_srn }}</td>
	       <td>{{ $importStatus->to_srn }}</td>
	       <td>{{ $importStatus->dcode }}</td>
	       <td>{{ $importStatus->bcode }}</td>
	       <td>{{ $importStatus->vcode }}</td>
	       <td>{{ $importStatus->ward_no }}</td>
	       <td>{{ $importStatus->booth_no }}</td>
	       <td>{{ $importStatus->save_remarks }}</td>
	        
	   </tr>
	@endforeach
	</tbody>
</table>