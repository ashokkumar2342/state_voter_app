<!DOCTYPE html>
<html>
<head>
<style>
 table,th, td {
  border: 1px solid black;
  border-collapse:collapse;
  text-align:center;
  padding:2px;
}
@page { footer: html_otherpagesfooter; }
</style>
</head>
<body>
	<htmlpagefooter name="otherpagesfooter" style="display:none">
		<div style="text-align:right;">
			{nbpg}  {PAGENO}
		</div>
	    
	</htmlpagefooter>
<table>
<tbody>
<tr>
<td style="width: 750px;background-color: #767d78;color: #fff;text-align: center;"><b>Village : {{ $village[0]->name_e }} , Ward No. : {{ $wardVillage[0]->ward_no }}</b></td>
</tr>
</tbody>
</table>
 <table style="width: 750px">
		<thead>
			<tr>
                <th style="width: 80px">Ac Code</th>
                <th style="width: 80px">Part No.</th> 
				<th style="width: 55px">Sr.No.</th> 
                <th style="width: 150px">Name</th>
                <th style="border-style:none"></th>
                <th style="width: 80px">Ac Code</th>
                <th style="width: 80px">Part No.</th> 
				<th style="width: 55px">Sr.No.</th> 
                <th style="width: 150px">Name</th>

                 
                

                 
			</tr>
		</thead>
		<tbody>
			@php
          $time =0;
        @endphp
	       @foreach ($voterReports as $voterReport)
	       @if ($time==0)
	       <tr>
	       @endif
	       @if ($time==1)
	       	<td style="border-style:none"></td>
	       @endif 
	        <td>{{ $voterReport->code }}</td>
	        <td>{{ $voterReport->part_no }}</td> 
	        <td>{{ $voterReport->sr_no }}</td>
			<td>{{ $voterReport->name_l }}&nbsp;</td>
			 
			 
	       @if ($time ==1)
	         </tr>
	       @endif
	         @php
	           $time ++;
	         @endphp
	         @if ($time==2)
	          @php
	            $time=0;
	          @endphp
	         @endif
	        @endforeach 
		</tbody>
	</table>
</body>
</html>
