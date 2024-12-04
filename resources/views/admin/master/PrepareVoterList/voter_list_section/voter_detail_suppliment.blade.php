		@php
		$time=0;
		$cpageno =1;
		$newpagestart = 0;
		if ($PrintedRows==0){
			$newpagestart = 1;
		}
		$counter = $PrintedRows*3;
		$totalCount=count($voterReports);
		$headerheight = 20;
		$i=0;
		$printsuchi = 1;
		@endphp
		@foreach ($voterReports as $voterReport)
		@php
		 	if ($newpagestart==1){
		 @endphp

		 		<table width = "100%" style="padding: 2px;font-size: 12px;font-weight: bold;">
		 			<tr>
		 				<td style="text-align: left; padding-left: 5px" width="25%">
		 					{{ $mainpagedetails[0]->village_mc_type }} : {{ $mainpagedetails[0]->village}}
		 				</td>
		 				<td style="text-align: center;" width="50%">
		 					{{ $mainpagedetails[0]->voter_list_type }} निर्वाचन नामावली  {{ $mainpagedetails[0]->year }}
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
		@if ($time==0)
		<table style="padding:-2px">
			<tbody>
				<tr>
					@endif  
					<td> 
						<table style="border:1px solid black;
						font-size:11px;padding:-2px;width: 220;height: 120px">
						<tbody>
							<tr>
								<td style="border: 1px solid black;width: 40px">{{ $voterReport->print_sr_no }}</td>
								<td style="width: 100px;text-align:center">{{ $voterReport->voter_card_no }}</td>
								<td style="border: 1px solid black;">&nbsp;{{ $voterReport->part_srno }}</td>
							</tr>
							<tr>
								<td style="width: 130px" colspan="2">नाम&nbsp; &nbsp; {{ $voterReport->name_l }}&nbsp;</td>
								<td style="text-align:center;" rowspan="4">
									@php
									if ($printphoto==1){
								 	  	$dirpath = '/app/vimage/'.$voterReport->assembly_id.'/'.$voterReport->assembly_part_id;
								 	  	$name =$voterReport->id;
								      	$image  =\Storage_path($dirpath.'/'.$name.'.jpg');
							 		@endphp
							 		<img src="{{ $image }}" alt="" width="65px" height="70px">
									@php
									}	
									@endphp
								</td>
							</tr>
							<tr>
								<td style="width: 130px" colspan="2">{{ $voterReport->vrelation }}&nbsp; &nbsp; {{ $voterReport->father_name_l }}&nbsp;</td>
							</tr>
							<tr>
								<td style="" colspan="2">मकान नं०&nbsp; &nbsp; &nbsp;{{ $voterReport->house_no_l }}&nbsp;</td>
							</tr>
							<tr>
								<td style="" colspan="2">आयु&nbsp; &nbsp; &nbsp;{{ $voterReport->age }} &nbsp; &nbsp;लिंग&nbsp; &nbsp; &nbsp; {{ $voterReport->genders_l }}</td>
							</tr>
						</tbody>
					</table> 
				</td> 
				@if($time==2 || $totalCount==$i)
					</tr>
				</tbody>

			</table>

		@endif
		@php
		$time ++;
		@endphp
		@if ($time==3)
		@php
		$time=0;
		@endphp
		@endif 


		@php
			$counter++;
			if($counter==27){$counter=0;$cpageno++;$newpagestart = 1;
		@endphp	

			<table width="100%" style="margin-top:5px;">
				<tr>
					<td width="48%" style="text-align: left;font-size: 11px;word-spacing: 4px"><b>*</b> {{ $mainpagedetails[0]->year }} को अंतिम प्रकाशित विधानसभा मतदाता सूचि का क्रo/भाग  नo आयु {{ $mainpagedetails[0]->date }} के अनुसार संशोधित </td> 
					<td width="52%" align="right" style="text-align: right;font-size: 12px;word-spacing: 4px"> कुल {{$totalpage}} पृष्ठों का पृष्ठ {{$cpageno}}</td>
				        
				</tr>
			</table>
			<pagebreak>

		@php
			}

		@endphp

		@endforeach
</div>
