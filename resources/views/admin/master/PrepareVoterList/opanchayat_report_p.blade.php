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
	@foreach ($WardVillages as $WardVillage)
	@php
	$voterListMaster=App\Model\VoterListMaster::where('status',1)->first(); 
	$PrepareVoterListSave= Illuminate\Support\Facades\DB::select(DB::raw("call up_process_voterlist ('$WardVillage->id')"));  
	$mainpagedetails= Illuminate\Support\Facades\DB::select(DB::raw("Select * From `main_page_detail` where `voter_list_master_id` =$voterListMaster->id and `ward_id` =$WardVillage->id;")); 
	$voterssrnodetails = Illuminate\Support\Facades\DB::select(DB::raw("Select * From `voters_srno_detail` where `voter_list_master_id` =$voterListMaster->id and `wardid` =1;"));
	$voterReports = Illuminate\Support\Facades\DB::select(DB::raw("select `v`.`print_sr_no`, `v`.`voter_card_no`, case `source` when 'V' then concat('*', `ap`.`part_no`, '/', `v`.`sr_no`) Else 'New' End as `part_srno`, `v`.`name_l`, `r`.`relation_l` as `vrelation`, `v`.`father_name_l`, `v`.`house_no_l`, `v`.`age`, `g`.`genders_l`, `vi`.`image` From `voters` `v` inner join `assembly_parts` `ap` on `ap`.`id` = `v`.`assembly_part_id` Inner Join `genders` `g` on `g`.`id` = `v`.`gender_id` Inner Join `voter_image` `vi` on `vi`.`voter_id` = `v`.`id` Inner Join `relation` `r` on `r`.`id` = `v`.`relation` where `v`.`ward_id` =$WardVillage->id And `v`.`status` in (0,1,3) Order By `v`.`print_sr_no`;"));
	@endphp

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
</htmlpagefooter>
<div style="text-align:center;"><h2><b>मुख्य पृष्ठ</b></h2></div>
@foreach ($mainpagedetails as $mainpagedetail)
<table style="height: 468px;" width="607">
<tbody>
<tr style="height: 52px;">
<td style="width: 94px; height: 52px;" colspan="6">
	{{ $mainpagedetail->year }} {{ $mainpagedetail->list_type }} {{ $mainpagedetail->election_type }} मतदाता सूचि सम्बन्धित विधानसभा क्षेत्र का नाम : {{ $mainpagedetail->district }}
</td>
</tr>
<tr style="height: 142px;">
<td style="width: 94px; height: 142px;" colspan="3">
	जिला का नाम : {{ $mainpagedetail->district }}
</td>
<td style="width: 95px; height: 142px;" colspan="3">
 <table>
	<thead>
		<tr>
			<th style="text-align:center">क्र०से</th>
			<th style="text-align:center">क्र तक</th>
			<th style="text-align:center">क्र०से</th>
			<th style="text-align:center">क्र तक</th>
			<th style="text-align:center">क्र०से</th>
			<th style="text-align:center">क्र तक</th>
			 
		</tr>
	</thead>
	<tbody>
		@php
      $time =0;
    @endphp
       @foreach ($voterssrnodetails as $voterssrnodetail)
       @if ($time==0)
       <tr>
       @endif 
         <td style="text-align:center"> {!! $voterssrnodetail->fromsrno !!}</td>
         <td style="text-align:center"> {!! $voterssrnodetail->tosrno !!} </td>
       @if ($time ==2)
         </tr>
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
	</tbody>
</table>
</td>
</tr>
<tr style="height: 159px;">
<td style="width: 94px; height: 159px;" colspan="3">&nbsp;</td>
<td style="width: 95px; height: 159px;">
	1. (क) ग्राम पंचायत का नाम व वार्ड संख्या :
	<br>
	(ख) खंड का नाम  :
	<br>
	(ग) पंचायत समिति का नाम व वार्ड संख्या :
	<br>
	(घ) जिला परिषद व वार्ड संख्या: 
</td>
<td style="width: 95px; height: 159px;">
	{{ $mainpagedetail->village }}
	<br>
	{{ $mainpagedetail->block }}
	<br>
	{{ $mainpagedetail->block }}
	<br>
	{{ $mainpagedetail->district }} 
</td>
<td style="width: 95px; height: 159px;">
	{{ $mainpagedetail->ward }}
	<br>
	<br>
	{{ $mainpagedetail->ps_ward }}
	<br>
	{{ $mainpagedetail->zp_ward }}
</td>
</tr>
<tr style="height: 52px;">
<td style="width: 94px; height: 52px;" colspan="6">
	2- पुनरीक्षण  का विवरण 
</td>
</tr>
<tr style="height: 52px;">
<td style="width: 94px; height: 52px;" colspan="3">
	पुनरीक्षण का वर्ष : <b>{{ $mainpagedetail->year }}</b>
	<br>
	पुनरीक्षण की तिथि  : <b>{{ $mainpagedetail->publication_date }}</b>
	<br>
	पुनरीक्षण का स्वरूप  : <b>{{ $mainpagedetail->list_type }}  {{ $mainpagedetail->year }}</b>
	<br>
	प्रकाशन की तिथि : <b>{{ $mainpagedetail->publication_date }}</b>  
</td>
<td style="width: 95px; height: 52px;" colspan="3">
	नामावली  पहचान  : 
			<br>
			<br>
			नये परिसमिति निर्वाचन क्षेत्रो के विस्तारानुसार सभी अनुपूरकों सहित एकीकृत व  वर्ष <b>{{ $mainpagedetail->year }}</b> की पुनरीक्षित मूल निर्वाचक नामावली
</td>
</tr>
<tr style="height: 52px;">
<td style="width: 94px; height: 52px;" colspan="6">मतदाताओं की संख्या</td>
</tr>
<tr style="height: 52px;">
<td style="text-align:center;width: 94px; height: 52px;">आरंभिक क्रम संख्या</td>
<td style="text-align:center;width: 94px; height: 52px;">अंतिम  क्रम संख्या</td>
<td style="text-align:center;width: 94px; height: 52px;">पुरुष</td>
<td style="text-align:center;width: 95px; height: 52px;">महिला</td>
<td style="text-align:center;width: 95px; height: 52px;">अन्य</td>
<td style="text-align:center;width: 95px; height: 52px;">कुल</td>
</tr>
<tr style="height: 52px;">
<td style="text-align:center;width: 94px; height: 52px;">{{ $mainpagedetail->from_sr_no }}</td>
<td style="text-align:center;width: 94px; height: 52px;">{{ $mainpagedetail->total }}</td>
<td style="text-align:center;width: 94px; height: 52px;">{{ $mainpagedetail->male }}</td>
<td style="text-align:center;width: 95px; height: 52px;">{{ $mainpagedetail->female }}</td>
<td style="text-align:center;width: 95px; height: 52px;">{{ $mainpagedetail->transgender }}</td>
<td style="text-align:center;width: 95px; height: 52px;">{{ $mainpagedetail->total }}</td>
</tr>
</tbody>
</table>
<div style="page-break-before: always;"></div>@php
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

	<pagebreak resetpagenum="1">
	@endforeach
</body>
</html>