		@php
			$headerheight = 20;
			$totalrowsprinted = $totalnewrows + $totaldeletedrows;
			$rowsremaining = fmod($totalrowsprinted, $rowsPerPage);
			if ($rowsremaining>0){
				$rowsremaining = $rowsPerPage-$rowsremaining;
			}
			$lrem=(int)($rowsremaining*100) - $headerheight;
			$printfooter = 0;
			if ($rowsremaining == 0){
				if($voterdeletedcount>0){
					$printfooter = fmod($voterdeletedcount, 3);
				}elseif($votercount>0){
					$printfooter = fmod($votercount, 3);
				}
			}else{
				$printfooter = 1;
			}
		@endphp
			
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
			<table width="100%" style="margin-top:5px;">
				<tr>
					<td width="75%" style="text-align: left;font-size: 11px;word-spacing: 4px">
						&nbsp;</td> 
					<td width="25%" align="right" style="text-align: right;font-size: 12px;word-spacing: 4px"> कुल {{$totalpage}} पृष्ठों का पृष्ठ {{$filelastpageno}}
					</td>
					    
				</tr>
			</table>
			

		@php
			}

		@endphp