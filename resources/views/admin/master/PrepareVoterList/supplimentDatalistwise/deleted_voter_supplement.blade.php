		@php
		$time=0;
		// $cpageno =1;
		$newpagestart = 0;
		if ($PrintedRows==0){
			$newpagestart = 1;
		}
		$counter = $PrintedRows*3;
		$totalCount=count($voterReports);
		$headerheight = 20;
		$i=0;
		$printsuchi = 1;
		$printtablehead = 1;

		// $cpageno = (int)($PrintedRows/9);
  //       if ($cpageno*9<$PrintedRows){$cpageno++;}
        // $cpageno++;

        $counter = fmod($PrintedRows,9)*3;

		@endphp
		@foreach ($voterReports as $voterReport)
		@php
		 	if ($newpagestart==1){$printtablehead=1;
		 @endphp

		 		<table width = "100%" style="padding: 2px;font-size: 12px;font-weight: bold;">
		 			<tr>
		 				<td style="text-align: left; padding-left: 5px" width="25%">
		 					{{ $mainpagedetails[0]->village_mc_type }} : {{ $mainpagedetails[0]->village}}
		 				</td>
		 				<td style="text-align: right; padding-right: 5px" width="25%">
		 					वार्ड संख्या : {{ $mainpagedetails[0]->ward}}
		 				</td>
		 				<td style="text-align: center;" width="50%">
		 					जिला : {{ $mainpagedetails[0]->district }}
		 				</td>
		 				
		 			</tr>
		 		</table>
		 	@php
		 		if ($mainpagedetails[0]->booth_id>0){
		 	@endphp

		 		<table width = "100%" style="padding: 2px;font-size: 12px;font-weight: bold;margin-top:-10px;">
		 			<tr>
		 				<td style="text-align: left; padding-left: 5px" width="100%">
		 					मतदान केन्द्र संख्या व नाम: {{ $mainpagedetails[0]->booth_no }} - {{ $mainpagedetails[0]->booth_name}}
		 				</td>
		 			</tr>
		 		</table>
		 @php
		 		$headerheight = 35;
		 		}
		 		$newpagestart=0;
		 	}
		 @endphp
		 	@if ($printsuchi==1)	
		 		<table width = "100%" style="padding: 2px;font-size: 12px;font-weight: bold;margin-top:10px;">
 		 			<tr>
 		 				<td style="text-align: left; padding-left: 15px" width="100%">
 		 					{{$SuchiType}}
 		 				</td>
 		 			</tr>
 		 		</table>
 		 		@php
 		 			$printsuchi = 0;
 		 		@endphp
 		 	@endif
		 @php
		 	$i=$i+1;
		 @endphp
		

		@php
			if($printtablehead == 1){ $printtablehead = 0;
		@endphp
		<table width = "100%" style="border: 1px solid black;font-size:13px;padding:0px;border-collapse:collapse;">
			<tbody>
				<tr>
					<td width = "15%" style="border: 1px solid black;text-align:center;font-weight: bold;height: 28px;">क्रमांक</td>
					<td width = "25%" style="border: 1px solid black;text-align:center;font-weight: bold;">मूल नामावली में क्रमांक संख्या</td>
					<td width = "60%" style="border: 1px solid black;text-align:left;font-weight: bold;padding-left: 5px;">निर्वाचक / मतदाता का नाम</td>
				</tr>
		@php
			}
		@endphp
				<tr>
					<td width = "15%" style="border: 1px solid black;text-align:center;height: 20px;">&nbsp;{{ $voterReport->print_sr_no }}</td>
					<td width = "25%" style="border: 1px solid black;text-align:center">&nbsp;{{ $voterReport->part_srno }}</td>
					<td width = "60%" style="border: 1px solid black;text-align:left;padding-left: 5px;">&nbsp;{{ $voterReport->name_l }}&nbsp;</td>
				</tr>
			

		@php
			$counter++;
			if($counter==27){$counter=0;$cpageno++;$newpagestart = 1;
		@endphp	
			</tbody>
		</table>
			<table width="100%" style='margin-top:5px;'>
				<tr>
					<td width="75%" style="text-align: left;font-size: 11px;word-spacing: 4px">
						@foreach ($rsDataListRemarks as $remarks)
							<b>{{ $remarks->tag }}</b> {{ $remarks->remarks }}, &nbsp;&nbsp;	
						@endforeach
						आयु {{ $mainpagedetails[0]->date }} के अनुसार संशोधित </td> 
					<td width="25%" align="right" style="text-align: right;font-size: 12px;word-spacing: 4px"> कुल {{$totalpage}} पृष्ठों का पृष्ठ {{$cpageno}}</td>
						        
				</tr>
			</table>
			<pagebreak>

		@php
			}

		@endphp

		@endforeach

		@php
			if($counter<27 && $counter>0){
		@endphp	
			</tbody>
		</table>
		@php
			}
		@endphp
