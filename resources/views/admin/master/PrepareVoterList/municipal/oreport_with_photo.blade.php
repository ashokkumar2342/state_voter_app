<!DOCTYPE html>
<html>
<head>
	<title></title>
	<style>
		table,th, td {
  
  border-collapse:collapse;
   
 
}
		@page {  
		    header: html_otherpageheader;
		    footer: html_otherpagesfooter;
		}

		@page :first {    
		    header: html_firstpageheader;
		     
		}
	</style>
</head>
<body>
	<htmlpageheader name="firstpageheader" style="display:none">
	    <div style="text-align:center;"><h2><b>Annexure-A</b></h2></div> 
	</htmlpageheader>

	<htmlpagefooter name="firstpagefooter" style="display:none">
	    <div style="text-align:center">{nbpg}  {PAGENO} </div>
	     
	</htmlpagefooter>

	<htmlpageheader name="otherpageheader" style="display:none">
		<table width="100%" style="border-bottom: 1px solid #000;font-weight: bold;word-spacing: 4px">
		    <tr>
		        <td width="25%" style="text-align: left;font-size: 11px"><h2>पंचायत : {{ $mainpagedetails[0]->district }}</h2></td> 
		        <td width="50%" align="right" style="text-align: right;font-size: 12px"><h2>{{ $mainpagedetails[0]->voter_list_type }} निर्वाचन नामावली {{ $mainpagedetails[0]->year }}</h2></td>
		        <td width="25%" align="right" style="text-align: right;font-size: 12px"><h2>वार्ड संख्या : {{ $mainpagedetails[0]->ward }}</h2></td> 
		    </tr>
		</table> 
	</htmlpageheader>

	<htmlpagefooter name="otherpagesfooter" style="display:none">
		<table width="100%" style="margin-top:5px;">
		    <tr>
		        <td width="50%" style="text-align: left;font-size: 11px;word-spacing: 4px"><b>*</b> {{ $mainpagedetails[0]->year }} को अंतिम प्रकाशित विधानसभा मतदाता सूचि का क्रo/भाग  नo आयु {{ $mainpagedetails[0]->publication_date }} के अनुसार संशोधित </td> 
		        <td width="50%" align="right" style="text-align: right;font-size: 12px;word-spacing: 4px">{nbpg}  {PAGENO}</td>
		        
		    </tr>
		</table>
	    
	</htmlpagefooter>
<div style="text-align:center;"><h2><b>मुख्य पृष्ठ</b></h2></div>
@foreach ($mainpagedetails as $mainpagedetail)
<table style="border: 1px solid black;">
<tbody>
<tr style="border: 1px solid black;">
<td style="border: 1px solid black;height: 40px;word-spacing:4px" colspan="5">&nbsp;&nbsp;{{ $mainpagedetail->year }} {{ $mainpagedetail->list_type }} {{ $mainpagedetail->election_type }} मतदाता सूचि सम्बन्धित विधानसभा क्षेत्र का नाम : {{ $mainpagedetail->district }}</td>
</tr>
<tr style="border: 1px solid black;">
<td style="border: 1px solid black;height: 40px;word-spacing:4px;padding-left: 20px" colspan="5"><h3><b>जिला का नाम : {{ $mainpagedetail->district }}</b></h3></td>
 
</tr>
<tr style="border: 1px solid black;">
<td style="border: 1px solid black;height: 150px;word-spacing: 4px" colspan="5">
<table style="word-spacing: 4px">
<tbody>
<tr>
<td style="padding-left: 20px;width: 300px">1. वार्ड संख्या</td>
<td style="width:200px;height: 40px"><b>{{ $mainpagedetail->ward }}</b></td> 
</tr> 
</tbody>
</table> 
</td>
</tr>
<tr style="border: 1px solid black;">
<td style="border: 1px solid black;height: 40px;word-spacing: 4px" colspan="5">&nbsp;&nbsp;2- पुनरीक्षण  का विवरण   </td>
</tr>
<tr style="border: 1px solid black;">
<td style="border: 1px solid black;height: 120px;word-spacing: 4px" colspan="2">
	
&nbsp;&nbsp;&nbsp;पुनरीक्षण का वर्ष : <b>{{ $mainpagedetail->year }}</b>
<br>
<br>
&nbsp;&nbsp;&nbsp;पुनरीक्षण की तिथि  : <b>{{ $mainpagedetail->publication_date }}</b>
<br>
<br>
&nbsp;&nbsp;&nbsp;पुनरीक्षण का स्वरूप  : <b>{{ $mainpagedetail->list_type }}  {{ $mainpagedetail->year }}</b> 
<br> 
<br> 
&nbsp;&nbsp;&nbsp;प्रकाशन की तिथि : <b>{{ $mainpagedetail->publication_date }}</b>  
</td>
<td style="border: 1px solid black;height: 120px;word-spacing: 4px;padding-left: 20px" colspan="3">
	
	नामावली  पहचान  : 
	<br>
	<br>
	नये परिसमिति निर्वाचन क्षेत्रो के विस्तारानुसार सभी अनुपूरकों सहित एकीकृत व  वर्ष <b>{{ $mainpagedetail->year }}</b> की पुनरीक्षित मूल निर्वाचक नामावली 
</td>
</tr>
<tr style="border: 1px solid black;">
<td style="border: 1px solid black;height: 40px;word-spacing: 4px" colspan="5">&nbsp;&nbsp;मतदाताओं क संया </td>
</tr>
<tr style="border: 1px solid black;">
<td style="border: 1px solid black;height: 40px;text-align:center;word-spacing: 4px">आरंभिक क्रम संख्या </td>
<td style="border: 1px solid black;height: 40px;text-align:center;word-spacing: 4px">अंतिम  क्रम संख्या </td>
<td style="border: 1px solid black;height: 40px;text-align:center;word-spacing: 4px">पुरुष</td>
<td style="border: 1px solid black;height: 40px;text-align:center;word-spacing: 4px">महिला</td>
<td style="border: 1px solid black;height: 40px;text-align:center;word-spacing: 4px">कुल</td>
</tr>
<tr style="border: 1px solid black;">
<td style="border: 1px solid black;height: 40px;text-align:center;word-spacing: 4px">{{ $mainpagedetail->from_sr_no }}</td>
<td style="border: 1px solid black;height: 40px;text-align:center;word-spacing: 4px">{{ $mainpagedetail->total }}</td>
<td style="border: 1px solid black;height: 40px;text-align:center;word-spacing: 4px">{{ $mainpagedetail->male }}</td>
<td style="border: 1px solid black;height: 40px;text-align:center;word-spacing: 4px">{{ $mainpagedetail->female }}</td>
<td style="border: 1px solid black;height: 40px;text-align:center;word-spacing: 4px">{{ $mainpagedetail->total }}</td>
</tr>
</tbody>
</table>
<div style="page-break-before: always;"></div>
@php
$time=0;
$totalCount=count($voterReports);
$i=0;
@endphp
@foreach ($voterReports as $voterReport)
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
						<td style="width: 130px" colspan="2">नाम&nbsp; &nbsp; {{ $voterReport->name_l }}</td>
						<td style="text-align:center;" rowspan="4">
							@php
						 	  $image=$voterReport->image;
						 	  $name =rand(100000,999999);
						      $image= \Storage::disk('local')->put("image/".$name.'.jpg', $image);
						      $image  =\Storage_path("app/image/".$name.'.jpg');
					 		 @endphp
					 		 <img src="{{ $image }}" alt="" width="65px" height="70px">
							{{-- @if ($i==1)
									@php
									$photo = "<img src=\"data:image/jpeg;base64, ".$voterReports[0]->image."\"/>";

								    @endphp
								    <img alt="" src="data:image/png;base64,{{ $voterReports[0]->image }}">
								{{ $voterReport->image }}
							@endif
							{{ $voterReport->image }} --}}
						</td>
					</tr>
					<tr>
						<td style="width: 130px" colspan="2">{{ $voterReport->vrelation }}&nbsp; &nbsp; {{ $voterReport->father_name_l }}</td>
					</tr>
					<tr>
						<td style="" colspan="2">मकान नं०&nbsp; &nbsp; &nbsp;{{ $voterReport->house_no_l }}</td>
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
@endforeach
@endforeach
</body>
</html>