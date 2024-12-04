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
		 					{{ $ac_name }}
		 				</td>
		 				<td style="text-align: center;" width="50%">
		 					भाग संख्या,  {{ $part_no }}
		 				</td>
		 				<td style="text-align: right; padding-right: 5px" width="25%">
		 					&nbsp;
		 				</td>
		 			</tr>
		 		</table>
		 	@php
		 		
		 	@endphp

		 		
		 @php
		 		$headerheight = 35;
		 		
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
						<table style="border:1px solid black;font-size:11px;padding:-5px;width: 220px;height: 70px;min-height: 70px; min-width: 220px;">
						<tbody>
							<tr>
								<td width = "100%">
									<table width = "100%">
										<tr>
											<td style="border: 1px solid black;width: 40px;">{{ $voterReport->sr_no }}</td>
											<td style="width: 100px;text-align:center">{{ $voterReport->voter_card_no }}</td>
											<td style="border: 0px solid black;width:80px;">&nbsp;</td>
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
													<tr><td>मकान नं०&nbsp; &nbsp; &nbsp;{{ $voterReport->house_no_l }}&nbsp;</td></tr>
													<tr><td>आयु&nbsp; &nbsp; &nbsp;{{ $voterReport->age }} &nbsp; &nbsp;लिंग&nbsp; &nbsp; &nbsp; {{ $voterReport->genders_l }}</td>
													</tr>
												</table>
											</td>
											<td width="75px" style="margin-top: -8px;">
												@php
													
												 	  	$dirpath = '/vimage/'.$voterReport->data_list_id.'/'.$voterReport->assembly_id.'/'.$voterReport->assembly_part_id;
												 	  	$name =$voterReport->sr_no;
												      	
												      	$image = \Storage::disk('voterimage')->url($dirpath.'/'.$name.'.jpg');
												      	
											 		@endphp
											 		@if($print_photo == 1)
											 			<img src="{{ $image }}" alt="" width="65px" height="70px">
													@endif
														
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
						&nbsp;</td> 
					<td width="25%" align="right" style="text-align: right;font-size: 12px;word-spacing: 4px"> कुल {{$totalpage}} पृष्ठों का पृष्ठ {{$cpageno}}</td>
				        
				</tr>
			</table>
			<pagebreak>

		@php
			}

		@endphp

		@endforeach

		
</div>
