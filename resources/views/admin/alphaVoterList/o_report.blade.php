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
					<td style="width: 750px;background-color: #767d78;color: #fff;text-align: center;"><b>District : {{ $district_name }} , Block : {{ $block_name }} , Panchayat : {{ $village_name }}</b></td>
				</tr>
			</tbody>
		</table>			 
	</htmlpageheader>
 
 <table style="width: 750px">
		<thead>
			<tr>
				<th style="width: 120px">Name</th>
                <th style="width: 120px">F/H Name </th>
                <th style="width: 50px">Ward No.</th>
                <th style="width: 50px">Sr. No.</th>
                <th style="width: 50px">Booth No.</th>
                <th style="border-style:none"></th> 
                <th style="width: 120px">Name</th>
                <th style="width: 120px">F/H Name </th>
                <th style="width: 50px">Ward No.</th>
                <th style="width: 50px">Sr. No.</th>
                <th style="width: 50px">Booth No.</th> 
			</tr>
		</thead>
		<tbody>
			@php
          $time =0;
        @endphp
	       @foreach ($rs_records as $val_records)
	       @if ($time==0)
	       <tr>
	       @endif 
	       @if ($time==1)
	       	<td style="border-style:none"></td>
	       @endif
	        
	        <td style="font-size: 11px;">{{ $val_records->name_e }}</td>
			<td style="font-size: 11px;">{{ $val_records->father_name_e }}&nbsp;</td>
			<td style="font-size: 11px;">{{ $val_records->ward_no }}&nbsp;</td>
			<td style="font-size: 11px;">{{ $val_records->print_sr_no }}</td> 
			<td style="font-size: 11px;">{{ $val_records->booth_no }}</td> 
			 
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
