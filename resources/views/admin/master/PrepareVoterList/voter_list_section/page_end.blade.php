			<table width="100%" style="margin-top:5px;">
				<tr>
					<td width="75%" style="text-align: left;font-size: 11px;word-spacing: 4px">
						@foreach ($rsDataListRemarks as $remarks)
							<b>{{ $remarks->tag }}</b> {{ $remarks->remarks }}, &nbsp;&nbsp;	
						@endforeach
						आयु {{ $mainpagedetails[0]->date }} के अनुसार संशोधित </td> 
					<td width="25%" align="right" style="text-align: right;font-size: 12px;word-spacing: 4px"> कुल {{$totalpage}} पृष्ठों का पृष्ठ {{$cpageno}}
					</td>
					    
				</tr>
			</table>
			<pagebreak></pagebreak>

			<table width = "100%" style="padding: 2px;font-size: 12px;font-weight: bold;">
		 			<tr>
		 				<td style="text-align: left; padding-left: 5px" width="25%">
		 					{{ $mainpagedetails[0]->village_mc_type }} : {{ $mainpagedetails[0]->village}}
		 				</td>
		 				<td style="text-align: center;" width="50%">
		 					{{ $mainpagedetails[0]->voter_list_type }} निर्वाचन नामावली,  {{ $mainpagedetails[0]->year }}
		 				</td>
		 				<td style="text-align: right; padding-right: 5px" width="25%">
		 					वार्ड संख्या : {{ $mainpagedetails[0]->ward}}
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
		 		}
			@endphp