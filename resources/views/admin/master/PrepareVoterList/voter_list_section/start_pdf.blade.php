<!DOCTYPE html>
<html>
<head>
	<title></title>
	
	
	<style>
		 @page {
        margin-top: 10px;
		margin-bottom: -40px;
    }
		
	{{-- <style>
		
      	@page {  
		    header: html_otherpageheader;
		    footer: html_otherpagesfooter;
		}

		@page :first {    
		    header: html_firstpageheader;
		     
		}
	</style> --}}

	{{-- <htmlpageheader name="firstpageheader" style="display:none">
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
				<td width="25%" align="right" style="text-align: right;font-size: 12px"><h2>वार्ड संख्या : {{ $WardVillage->ward_no }}</h2></td> 
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
   	</htmlpagefooter> --}}

	 