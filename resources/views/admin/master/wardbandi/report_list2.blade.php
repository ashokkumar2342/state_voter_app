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
<td style="width: 750px;background-color: #767d78;color: #fff;text-align: center;"><b>Assembly : {{ $assembly[0]->code }} , Assembly Part : {{ $assemblyPart[0]->part_no }}</b></td>
</tr>
</tbody>
</table>
 <table style="width: 750px">
		<thead>
			<tr>
				<th>Sr.No.</th> 
                <th>Name </th>
                <th>F/H Name</th>
                <th style="border-style:none"></th>
                 
                <th>Sr.No.</th> 
                <th>Name </th>
                <th>F/H Name</th>
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
	        <td>{{ $voterReport->sr_no }}</td>
			 
			<td>{{ $voterReport->name_l }}</td>
			<td>{{ $voterReport->father_name_l }}</td> 
			 
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
