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
	    margin-top: 2.50cm;
	}
</style>
</head>
<body>
	<htmlpagefooter name="otherpagesfooter" style="display:none">
		<div style="text-align:right;">
			{nbpg}  {PAGENO}
		</div>
	    
	</htmlpagefooter>
	<htmlpageheader name="otherpageheader" style="display:none;padding-bottom: 50px;">
		<table width = "100%">
			<tbody>
				<tr>
					<td style="width: 100%;background-color: #767d78;color: #fff;text-align: center;"><b>{!!$report_heading!!}</b></td>
				</tr>
			</tbody>
		</table>			 
	</htmlpageheader>
	<div class="row" style="padding-top: 50px;"> 
	<div class="col-lg-12 table-responsive"> 
	    <table class="table" id="village_ward_sample_table" width = "100%">
	        <thead>
	          <tr> 
	          	<th>AC Part No.</th>
      			<th>Sr. No.</th>
      			<th>Voter Card No.</th>
      			<th>Name</th>
      			<th>House No.</th>
      		</tr>
	        </thead>
	        <tbody>
	         @foreach ($results as $result)
	      		<tr>
	      			<td>{{ $result->code}} - {{ $result->part_no}}</td>
	      			<td>{{ $result->sr_no}}</td>
					<td>{{ $result->voter_card_no }}</td>
					<td>{{ $result->name_l }}</td>
					<td>{{ $result->house_no_l }}</td>
					
	      		</tr> 
	      	@endforeach
	        </tbody>
	      
	    </table>
	</div>
	</div>
</body>
</html>

