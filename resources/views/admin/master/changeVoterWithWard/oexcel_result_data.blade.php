
<div class="col-lg-12 table-responsive"> 
<table class="table" class="table table-striped table-hover control-label">
	<thead>
	   <tr>  
	       <th>District Code</th>
	       <th>Block Code</th>
	       <th>Village Code</th>
	       <th>From Ward</th>
	       <th>From Booth</th>
	       <th>To Ward</th>
	       <th>To Booth</th>
	       <th>From Sr.No.</th>
	       <th>To Sr.No.</th>
	       <th>Remarks</th>
	        
	   </tr>
	</thead>
	<tbody>
	@foreach ($result_dates as $result_date) 
	   <tr style="{{ $result_date->status==1?'background-color: #b1333f':'' }}"> 
	       <td>{{ $result_date->district_code }}</td>
	       <td>{{ $result_date->block_code }}</td>
	       <td>{{ $result_date->village_code }}</td> 
	       <td>{{ $result_date->from_ward }}</td> 
	       <td>{{ $result_date->from_booth }}</td> 
	       <td>{{ $result_date->to_ward }}</td> 
	       <td>{{ $result_date->to_booth }}</td> 
	       <td>{{ $result_date->from_srno }}</td> 
	       <td>{{ $result_date->to_srno }}</td> 
	       <td>{{ $result_date->remarks }}</td> 
	   </tr>
	@endforeach
	</tbody>
</table>
</div>