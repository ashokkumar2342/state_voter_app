		@php
		$time=0;
		$cpageno =1;
		$newpagestart = 1;
		$counter = 0;
		$totalCount=count($voterReports);
		$headerheight = 20;
		$i=0;
		@endphp
		@foreach ($voterReports as $voterReport)
		@php
		 	if ($newpagestart==1){
		 @endphp	
		 @php
		 		$newpagestart=0;
		 	}
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
								<td style="border: 1px solid black;">&nbsp;{{ $voterReport->sr_no }}</td>
							</tr>
							<tr>
								<td style="width: 130px" colspan="2"></td>
								<td style="text-align:center;" rowspan="4">
									@php
									  	$dirpath = '/app/vimage/'.$voterReport->data_list_id.'/'.$voterReport->assembly_id.'/'.$voterReport->assembly_part_id;
								 	  	$name =$voterReport->id;
								 	  	$name =$voterReport->sr_no;
								      	$image  =\Storage_path($dirpath.'/'.$name.'.jpg');
							 		@endphp
							 		<img src="{{ $image }}" alt="" width="65px" height="70px">
								</td>
							</tr>
							<tr>
								<td style="width: 130px" colspan="2"></td>
							</tr>
							<tr>
								<td style="" colspan="2"></td>
							</tr>
							<tr>
								<td style="" colspan="2"></td>
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
			if($counter==30){$counter=0;$cpageno++;$newpagestart = 1;
		@endphp	

			<pagebreak>

		@php
			}

		@endphp

		@endforeach

		@php
			if($newpagestart == 0){$cpageno++;$remaining = 30-$counter;$lrem=(int)((30-$counter-fmod($remaining, 3))/3)*100-$headerheight;
		@endphp	
			<table width="100%" style="margin-top:{{$lrem}}px;" >
				<tr>
					<td>
						&nbsp;
					</td>
				        
				</tr>
			</table>
			

		@php
			}

		@endphp
</div>
