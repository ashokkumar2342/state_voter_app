		@php
			$headerheight = 20;
			$totalrowsprinted = $totalnewrows + $totalmodifiedrows + $totaldeletedrows;
			$rowsremaining = fmod($totalrowsprinted, 9);
			if ($rowsremaining>0){
				$rowsremaining = 9-$rowsremaining;
			}
			$lrem=(int)($rowsremaining*100) - $headerheight;
			$printfooter = 0;
			if ($rowsremaining == 0){
				if($votermodifiedcount>0){
					$printfooter = fmod($votermodifiedcount, 3);
				}elseif($voterdeletedcount>0){
					$printfooter = fmod($voterdeletedcount, 3);
				}elseif($votercount>0){
					$printfooter = fmod($votercount, 3);
				}
			}else{
				$printfooter = 1;
			}
		@endphp
			{{-- <table width="100%" style="margin-top:{{$lrem}}px;" >
				<tr>
					<td>
						TotalRows = {{$totalrowsprinted}}, rowsrem = {{$rowsremaining}}, margin = {{$lrem}}, printfooter = {{$printfooter}}, 
					</td>
				        
				</tr>
			</table> --}}
		@php
			if($printfooter > 0){
		@endphp	
			<table width="100%" style="margin-top:{{$lrem}}px;" >
				<tr>
					<td>
						&nbsp;
					</td>
				        
				</tr>
			</table>
			<table width="100%" style='margin-top:5px;'>
				<tr>
					<td width="75%" style="text-align: left;font-size: 11px;word-spacing: 4px"><b>*</b> {{ $mainpagedetails[0]->year }} को अंतिम प्रकाशित विधानसभा मतदाता सूचि का क्रo/भाग  नo <br><b>#</b> 31-10-2021 को प्रकाशित विधानसभा मतदाता सूचि का क्रo/भाग  नo , आयु {{ $mainpagedetails[0]->date }} के अनुसार संशोधित</td> 
					<td width="25%" align="right" style="text-align: right;font-size: 12px;word-spacing: 4px"> कुल {{$totalpage}} पृष्ठों का पृष्ठ {{$totalpage}}</td>
						        
				</tr>
			</table>

			

		@php
			}

		@endphp