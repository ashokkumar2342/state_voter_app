@php
	$counter = 0;
	$sectionprinted = 0;
	if($is_suppliment == 1){
		$h_caption = 'परिवर्धन ';
	}else{
		$h_caption = '';
	}
@endphp

<table id="headertable" style="border: 0px text-align: center;" width="100%">
	<tr>
		<td style="align-content: center; text-align: right;font-size: 14px; font-weight: bold;">
			Annexure-A
		</td>
	</tr>
	<tr>
		<td style="align-content: center; text-align: left;font-size: 14px; font-weight: bold;">
			{{ $mainpagedetails[0]->election_type }} पूरक निर्वाचक नामावली, {{ $mainpagedetails[0]->year }} 
		</td>
	</tr>
	<tr>
		<td>
			<table style="border: 1px solid black;" width="100%">
				<tr style="border: 1px solid black;" >
					<td style="align-content: center; text-align: left">
						जिला <br><br>{{ $mainpagedetails[0]->village_mc_type }} का नाम <br><br>वार्ड न. 
						@php
		 					if ($mainpagedetails[0]->booth_id>0){
		 				@endphp
		 					<br><br>मतदान केन्द्र संख्या व नाम
		 				@php
		 					}
		 				@endphp
					</td>
					<td style="align-content: center; text-align: left">
						: {{ $mainpagedetails[0]->district }}<br><br>: {{ $mainpagedetails[0]->village }} <br><br>: {{ $mainpagedetails[0]->ward }} 
						@php
		 					if ($mainpagedetails[0]->booth_id>0){
		 				@endphp
							<br><br>: {{ $mainpagedetails[0]->booth_no }} - {{ $mainpagedetails[0]->booth_name}} 
						@php
		 					}
		 				@endphp
					</td>
				</tr>
			</table>
			<table style="border: 1px solid black;" width="100%">
				<tr style="border: 1px solid black;" >
					<td width = "30%" style="align-content: center; text-align: left">
						परिशिष्ठ विवरण	
					</td>
					<td width = "70%" style="align-content: center; text-align: right">
						परिशिष्ठ संख्या : 1	
					</td>
				</tr>
			</table>
			<table style="border: 1px solid black;" width="100%">
				<tr style="border: 1px solid black;" >
					<td style="align-content: center; text-align: left">
						पुनरीक्षण पहचान	
					</td>
					<td style="align-content: center; text-align: right">
						पात्रता की तिथि : {{ $mainpagedetails[0]->date }}	
					</td>
				</tr>
			</table>
			<table style="border: 1px solid black;" width="100%">
				<tr style="border: 1px solid black;" >
					<td style="align-content: center; text-align: left">
						मूल नामावली <br><br>परिशिष्ठ प्रक्रिया व वर्ष <br><br>परिशिष्ठ का प्रकार<br><br>प्रकाशन की तिथि
					</td>
					<td style="align-content: center; text-align: left">
						: वर्ष {{ $mainpagedetails[0]->year }} तक के सभी अनुपूरकों सहित एकीकृत फोटोयुक्त निर्वाचक नामावली <br><br>: विशेष संक्षिप्त पुनरीक्षण 	{{ $mainpagedetails[0]->year }} <br><br>: परिवर्धन, विलोपन व संशोधन सूचि <br><br>: {{ $mainpagedetails[0]->publication_date }} 
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
<br><br><br>
		@foreach ($mainpagedetails as $mainpagedetail)
		<table id="detailtable" style="border: 1px solid black;" width="100%">
		<tbody>
		
		@if ($mainpagedetail->total>0)
		<tr>
			<td>
				<table width = "100%">
					<tr>
						<td style="border: 1px solid black;" width="100%"><b>परिवर्धनो की संख्या</b></td>
					</tr>
				</table> 
			</td>
		</tr>
		<tr>
			<td>
				<table width="100%" style="border-collapse:collapse;">
					<tr style="border: 1px solid black;">
						<td style="border: 1px solid black;height: 40px;text-align:center;word-spacing: 4px" width="17%">पुरुष</td>
						<td style="border: 1px solid black;height: 40px;text-align:center;word-spacing: 4px" width="17%">महिला</td>
						<td style="border: 1px solid black;height: 40px;text-align:center;word-spacing: 4px" width="17%">अन्य</td>
						<td style="border: 1px solid black;height: 40px;text-align:center;word-spacing: 4px" width="15%">कुल</td>
					</tr>
					<tr style="border: 1px solid black;">
						<td style="border: 1px solid black;height: 40px;text-align:center;word-spacing: 4px">{{ $mainpagedetail->male }}</td>
						<td style="border: 1px solid black;height: 40px;text-align:center;word-spacing: 4px">{{ $mainpagedetail->female }}</td>
						<td style="border: 1px solid black;height: 40px;text-align:center;word-spacing: 4px">{{ $mainpagedetail->transgender }}</td>
						<td style="border: 1px solid black;height: 40px;text-align:center;word-spacing: 4px">{{ $mainpagedetail->total }}</td>
					</tr>
				</table>
			</td>
		</tr>
		@endif

		@if ($mainpagedetail->deleted_total>0)
		<tr>
			<td>
				<table width = "100%">
					<tr>
						<td style="border: 1px solid black;" width="100%"><b>विलोपन मतदाताओं की संख्या</b></td>
					</tr>
				</table> 
			</td>
		</tr>
		<tr>
			<td>
				<table width="100%" style="border-collapse:collapse;">
					<tr style="border: 1px solid black;">
						<td style="border: 1px solid black;height: 40px;text-align:center;word-spacing: 4px" width="25%">पुरुष</td>
						<td style="border: 1px solid black;height: 40px;text-align:center;word-spacing: 4px" width="25%">महिला</td>
						<td style="border: 1px solid black;height: 40px;text-align:center;word-spacing: 4px" width="25%">अन्य</td>
						<td style="border: 1px solid black;height: 40px;text-align:center;word-spacing: 4px" width="25%">कुल</td>
					</tr>
					<tr style="border: 1px solid black;">
						<td style="border: 1px solid black;height: 40px;text-align:center;word-spacing: 4px">{{ $mainpagedetail->deleted_male }}</td>
						<td style="border: 1px solid black;height: 40px;text-align:center;word-spacing: 4px">{{ $mainpagedetail->deleted_female }}</td>
						<td style="border: 1px solid black;height: 40px;text-align:center;word-spacing: 4px">{{ $mainpagedetail->deleted_third }}</td>
						<td style="border: 1px solid black;height: 40px;text-align:center;word-spacing: 4px">{{ $mainpagedetail->deleted_total }}</td>
					</tr>
				</table>
			</td>
		</tr>
						
		@endif

		@if ($mainpagedetail->modified_total>0)
		<tr>
			<td>
				<table width = "100%">
					<tr>
						<td style="border: 1px solid black;" width="100%"><b>संशोधित मतदाताओं की संख्या</b></td>
					</tr>
				</table> 
			</td>
		</tr>
		<tr>
			<td>
				<table width="100%" style="border-collapse:collapse;">
					<tr style="border: 1px solid black;">
						<td style="border: 1px solid black;height: 40px;text-align:center;word-spacing: 4px" width="25%">पुरुष</td>
						<td style="border: 1px solid black;height: 40px;text-align:center;word-spacing: 4px" width="25%">महिला</td>
						<td style="border: 1px solid black;height: 40px;text-align:center;word-spacing: 4px" width="25%">अन्य</td>
						<td style="border: 1px solid black;height: 40px;text-align:center;word-spacing: 4px" width="25%">कुल</td>
					</tr>
					<tr style="border: 1px solid black;">
						<td style="border: 1px solid black;height: 40px;text-align:center;word-spacing: 4px">{{ $mainpagedetail->modified_male }}</td>
						<td style="border: 1px solid black;height: 40px;text-align:center;word-spacing: 4px">{{ $mainpagedetail->modified_female }}</td>
						<td style="border: 1px solid black;height: 40px;text-align:center;word-spacing: 4px">{{ $mainpagedetail->modified_third }}</td>
						<td style="border: 1px solid black;height: 40px;text-align:center;word-spacing: 4px">{{ $mainpagedetail->modified_total }}</td>
					</tr>
				</table>
			</td>
		</tr>				
		@endif

		
		</tbody>
		</table>
		

		

		@php
			if ($mainpagedetail->total>0){
				$sectionprinted = $sectionprinted + 1;
			}
			if ($mainpagedetail->modified_total>0){
				$sectionprinted = $sectionprinted + 1;
			}
			if ($mainpagedetail->deleted_total>0){
				$sectionprinted = $sectionprinted + 1;
			}
			$counter = 21-$counter;
			if ($counter>14){
				$counter=14;
			}
			$margintop = $counter*20 - ($sectionprinted-1)*100;
			// $margintop = 0;
		@endphp
		<table width="100%" style='margin-top:{{$margintop}}px;'>
			<tr>
				<td width="75%" style="text-align: left;font-size: 11px;word-spacing: 4px"><b>*</b> {{ $mainpagedetail->year }} को अंतिम प्रकाशित विधानसभा मतदाता सूचि का क्रo/भाग  नo <br><b>#</b> 31-10-2021 को प्रकाशित विधानसभा मतदाता सूचि का क्रo/भाग  नo , आयु {{ $mainpagedetail->date }} के अनुसार संशोधित</td> 
				<td width="25%" align="right" style="text-align: right;font-size: 12px;word-spacing: 4px"> कुल {{$totalpage}} पृष्ठों का पृष्ठ 1</td>
					        
			</tr>
		</table>
		@endforeach

		

	
	@push('scripts')
	<script type="text/javascript">findtablesheight();</script>
	@endpush
	
	
	<pagebreak>	

	