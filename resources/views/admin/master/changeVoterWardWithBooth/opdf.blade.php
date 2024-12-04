<!DOCTYPE html>
<html>
<head>
<style>
 table,th, td {
  border: 1px solid black;
  border-collapse:collapse;
  text-align:center;
 
}
@page { footer: html_otherpagesfooter; 
	    header: html_otherpageheader;
	}
</style>
</head>
<body>
	<htmlpagefooter name="otherpagesfooter" style="display:none">
		<div style="text-align:right;">
			{nbpg}  {PAGENO}
		</div>
	    
	</htmlpagefooter>
	<htmlpageheader name="otherpageheader" style="display:none">
		<table width = "100%">
			<tbody>
				<tr>
					<td style="width: 100%;background-color: #767d78;color: #fff;text-align: center;"><b>Change Voter Ward With Booth</b></td>
				</tr>
			</tbody>
		</table>			 
	</htmlpageheader>
	<div class="row"> 
	<div class="col-lg-12 table-responsive"> 
	    <table class="table" id="village_ward_sample_table" width = "100%">
	        <thead>
	          <tr>
      			<th>Print Sr. No.</th>
      			<th>Name</th>
      			<th>Father Name</th>
      			<th>Ward No.</th>
      			
      		</tr>
	        </thead>
	        <tbody>
	         @foreach ($results as $result)
	      		<tr>
	      			<td>{{ $result->print_sr_no}}</td>
	      			<td>{{ $result->name_e }}</td>
	      			<td>{{ $result->father_name_e }}</td>
	      			<td>{{ $result->ward_no }}</td> 
	      		</tr> 
	      	@endforeach
	        </tbody>
	      
	    </table>
	</div>
	</div>
</body>
</html>

