<table class="table">
	<thead>
	   <tr> 
	       <th>Sr. No.</th>
	       <th>Regis No.</th>
	       <th>Age</th>
	       <th>Sex</th>
	       <th>Name</th>
	       <th>Fathers Name</th>
	       <th>Mothers Name</th>
	       <th>Spouse Name</th>
	       <th>AC Name</th>
	       <th>AC Code</th>
	       <th>Part No.</th>
	       <th>Sl. No. in Part</th>
	       <th>Voter Card No.</th>
	   </tr>
	</thead>
	<tbody>
	@foreach ($SaveResult as $importStatus) 
	   <tr>
	       <td>{{ $importStatus->srno }}</td>
	       <td>{{ $importStatus->regisno }}</td>
	       <td>{{ $importStatus->age }}</td>
	       <td>{{ $importStatus->sex }}</td>
	       <td>{{ $importStatus->dec_name }}</td>
	       <td>{{ $importStatus->dec_fname }}</td>
	       <td>{{ $importStatus->dec_mname }}</td>
	       <td>{{ $importStatus->dec_spouse }}</td>
	       <td>{{ $importStatus->ac_name }}</td>
	       <td>{{ $importStatus->ac_code }}</td>
	       <td>{{ $importStatus->part_no }}</td>
	       <td>{{ $importStatus->srnoinpart }}</td>
	       <td>{{ $importStatus->voter_card_no }}</td>
	        
	   </tr>
	@endforeach
	</tbody>
</table>