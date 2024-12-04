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
		<table>
			<tbody>
				<tr>
					<td style="width: 750px;background-color: #767d78;color: #fff;text-align: center;"><b>Assembly : {{ $assembly[0]->code }} , Assembly Part : {{ $assemblyPart[0]->part_no }}</b></td>
				</tr>
			</tbody>
		</table>			 
	</htmlpageheader>
 
 <table style="width: 750px">
		<thead>
			<tr>
				<th style="width: 50px">Sr.No.</th>
                <th style="width: 120px">Name </th>
                <th style="width: 140px">Village</th>
                <th style="width: 50px">Wards</th>
                <th style="border-style:none"></th> 
                <th style="width: 50px;">Sr.No.</th>
                <th style="width: 120px">Name </th>
                <th style="width: 140px">Village</th>
                <th style="width: 50px">Ward</th>
                 
                 
                 
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
			<td>{{ $voterReport->name_l }}&nbsp;</td>
			<td>{{ $voterReport->vil_name }}&nbsp;</td>
			<td>{{ $voterReport->ward_no }}</td> 
			 
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
