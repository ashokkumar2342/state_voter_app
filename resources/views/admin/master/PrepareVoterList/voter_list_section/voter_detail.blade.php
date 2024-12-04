		@php
		$time=0;
		// $cpageno =1;
		$newpagestart = 0;
		if ($PrintedRows==0){
			$newpagestart = 1;
		}
		$totalCount=count($voterReports);
		$headerheight = 20;
		$i=0;
		if($SuchiType==''){
			$printsuchi = 0;
		}else{
			$printsuchi = 1;	
		}
		

		if($voter_per_page == 30){
			$counter = fmod($PrintedRows,10)*3;
		}else{
			$counter = fmod($PrintedRows,9)*3;	
		}
		

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
					{{-- @php
						$bgimage=0;
						if($voterReport->status==2){
							$dirpath = '/app/background/3.jpg';	
							$image  =\Storage_path($dirpath);
							$bgimage=1;
						}elseif($voterReport->status==3){
							$dirpath = '/app/background/m.jpg';
							$image  =\Storage_path($dirpath);	
							$bgimage=1;
						}
						
					@endphp
					@if ($bgimage==1)
						<td style="background-image: url('{{ $image }}'); width:200px;height: 50px;background-repeat: no-repeat;background-position: center;"> 	
					@else
						<td> 
					@endif --}}
					<td>
						<table style="border:1px solid black;font-size:11px;padding:-5px;width: 220px;height: 70px;min-height: 70px; min-width: 220px;">
						<tbody>
							<tr>
								<td width = "100%">
									<table width = "100%">
										<tr>
											<td style="border: 1px solid black;width: 40px;">{{ $voterReport->print_sr_no }}</td>
											<td style="width: 100px;text-align:center">{{ $voterReport->voter_card_no }}</td>
											<td style="border: 1px solid black;width:80px;">&nbsp;{{ $voterReport->part_srno }}</td>
										</tr>
									</table>
								</td>
							</tr>
							<tr>
								<td style="margin-top: -8px;">
									<table width = "100%" style="margin-top: -8px;">
										<tr>
											<td width="145px" style="margin-top: -4px;">
												<table width = "100%" style="padding: 0px;">
													<tr><td width = "100%">नाम&nbsp; &nbsp; {{ $voterReport->name_l }}&nbsp;</td></tr>
													<tr><td>{{ $voterReport->vrelation }}&nbsp; &nbsp; {{ $voterReport->father_name_l }}&nbsp;</td></tr>
													@if(strlen($voterReport->house_no_l) > 10)
														<tr><td>मकान नं०&nbsp; <span  style="font-size: 7px;">{{ substr($voterReport->house_no_l, 0, 15)}}&nbsp;</span></td></tr>
													@else
														<tr><td>मकान नं०&nbsp; &nbsp; &nbsp;{{ $voterReport->house_no_l }}&nbsp;</td></tr>
													@endif
													<tr><td>आयु&nbsp; &nbsp; &nbsp;{{ $voterReport->age }} &nbsp; &nbsp;लिंग&nbsp; &nbsp; &nbsp; {{ $voterReport->genders_l }}</td>
													</tr>
												</table>
											</td>
											<td width="75px" style="margin-top: -8px;">
												@php
													if ($printphoto==1){
												 	  	// $dirpath = '/vimage/'.$voterReport->data_list_id.'/'.$voterReport->assembly_id.'/'.$voterReport->assembly_part_id;
												 	  	$dirpath = 'app/vimage/'.$voterReport->data_list_id.'/'.$voterReport->assembly_id.'/'.$voterReport->assembly_part_id;
												 	  	$name =$voterReport->sr_no;
												      	$image = \Storage_path($dirpath.'/'.$name.'.jpg');
												      	// dd(\Storage_path($dirpath.'/'.$name.'.jpg'));
												      	// $image = \Storage::disk('voterimage').$dirpath.'/'.$name.'.jpg';
												      	// $image = \Storage::disk('voterimage')->url($dirpath.'/'.$name.'.jpg');
												      	// dd($image);
											 		@endphp
											 		<img src="{{ $image }}" alt="" width="65px" height="70px">
													@php
													}	
												@endphp
														
											</td>
										</tr>
									</table>
								</td>
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
			if($counter==$voter_per_page){$counter=0;$cpageno++;$newpagestart = 1;
		@endphp	

			<table width="100%" style="margin-top:5px;">
				<tr>
					<td width="75%" style="text-align: left;font-size: 11px;word-spacing: 4px">
						@foreach ($rsDataListRemarks as $remarks)
							<b>{{ $remarks->tag }}</b> {{ $remarks->remarks }}, &nbsp;&nbsp;	
						@endforeach
						आयु {{ $mainpagedetails[0]->date }} के अनुसार संशोधित </td> 
					<td width="25%" align="right" style="text-align: right;font-size: 12px;word-spacing: 4px"> कुल {{$totalpage}} पृष्ठों का पृष्ठ {{$cpageno}}</td>
				        
				</tr>
			</table>
			@php
				if($totalpage!=$cpageno){
			@endphp
				<pagebreak>
			@php
				}

			@endphp

		@php
			}

		@endphp

		@endforeach

		{{-- @php
			if($newpagestart == 0){$cpageno++;$remaining = 30-$counter;$lrem=(int)((30-$counter-fmod($remaining, 3))/3)*100-$headerheight;
		@endphp	
			<table width="100%" style="margin-top:{{$lrem}}px;" >
				<tr>
					<td>
						&nbsp;
					</td>
				        
				</tr>
			</table>
			<table width="100%" style="margin-top:5px;">
				<tr>
					<td width="48%" style="text-align: left;font-size: 11px;word-spacing: 4px"><b>*</b> {{ $mainpagedetails[0]->year }} को अंतिम प्रकाशित विधानसभा मतदाता सूचि का क्रo/भाग  नo आयु {{ $mainpagedetails[0]->date }} के अनुसार संशोधित </td> 
					<td width="52%" align="right" style="text-align: right;font-size: 12px;word-spacing: 4px"> कुल {{$totalpage}} पृष्ठों का पृष्ठ {{$cpageno}}</td>
				        
				</tr>
			</table>
			

		@php
			}

		@endphp --}}
</div>
