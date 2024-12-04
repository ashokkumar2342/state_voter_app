@php
	$counter = 0;
@endphp
@foreach ($voterReports as $voterReport) 
	<table width="100%">
		<tbody>
			<tr>
				<td style="text-align:center;font-size:20px;word-spacing:5px"><b>{{$slipheader}}</b></td>
			</tr>
		</tbody>
	</table>
	<table width="100%">
		<tbody>
			<tr>
				<td style="width: 100%; text-align:center;font-size:18px;word-spacing:5px"><b>Voter Slip</b></td>
			</tr>
		</tbody>
	</table>
	<table width="100%">
		<tbody>
			<tr style="height: 13px;">
				<td style="width: 80px; height: 12px;font-size:16px;word-spacing:4px;">वार्ड न० :</td>
				<td style="width: 74px; height: 12px;font-size:16px;word-spacing:4px"><b>{{$wardno}}</b></td>
				<td style="width: 93px; height: 12px;font-size:16px;word-spacing:4px">Part No. :</td>
				<td style="width: 249px; height: 12px;font-size:16px;word-spacing:4px"><b>{{$voterReport->part_no}}</b></td>
				<td style="width: 91px; height: 50px;" rowspan="4">
@php	
$dirpath = '/app/vimage/'.$voterReport->data_list_id.'/'.$voterReport->assembly_id.'/'.$voterReport->assembly_part_id;
$name =$voterReport->sr_no;
$image  =\Storage_path($dirpath.'/'.$name.'.jpg');
@endphp
					<img src="{{ $image }}" alt="" width="110px" height="110px">
				</td>
			</tr>
			<tr>
				<td style="font-size:16px;word-spacing:4px">नाम</td>
				<td style="font-size:16px;word-spacing:4px" colspan="3"><b>{{ $voterReport->name_l }}&nbsp;</b></td>
			</tr>
			<tr>
				<td style="font-size:16px;word-spacing:4px">लिंग</td>
				<td style="font-size:16px;word-spacing:4px"><b>{{$voterReport->genders_l}}</b></td>
				<td style="font-size:16px;word-spacing:4px">EPIC No. :</td>
				<td style="font-size:16px;word-spacing:4px"><b>{{ $voterReport->voter_card_no }}</b></td>
			</tr>
			<tr>
				<td style="font-size:16px;word-spacing:4px">{{ $voterReport->vrelation }}</td>
				<td style="font-size:16px;word-spacing:4px" colspan="3"><b>{{ $voterReport->father_name_l }}&nbsp;</b></td>
			</tr>
		</tbody>
	</table>
	<table width="100%">
		<tbody>
			<tr>
				<td style="width: 33%; font-size:14px;word-spacing:4px">मतदाता क्रमांक संख्या : </td>
				<td style="width: 67%; font-size:14px;word-spacing:4px"><b>{{ $voterReport->print_sr_no }}</b></td>
			</tr>
			<tr>
				<td style="font-size:14px;word-spacing:4px">Polling Station No. and Name : </td>
				<td style="font-size:14px;word-spacing:4px"><b>{{ $voterReport->boothno }} -  {{ $voterReport->pb_name }}</b></td>
			</tr>
			<tr>
				<td style="font-size:14px;word-spacing:4px">Poll Date, Day and Time : </td>
				<td style="font-size:14px;word-spacing:4px"><b>{{ $polldatetime[0]->polling_day_time_l }}</b></td>
			</tr>
			<tr>
				<td col span ="2">Note: </td>
			</tr>
			<tr>
				<td colspan="2">
					<table>
						@foreach ($slipNotes as $val_slip_note) 
							<tr>
								<td width ="5%">{{$val_slip_note->note_srno}}</td>
								<td width ="95%" style="text-align: justify-all;">{{$val_slip_note->note_text}}</td>
							</tr>
						@endforeach	
					</table>
			
				</td>
			</tr>
		</tbody>
	</table>
	<table style="width: 100%;">
		<tbody>
			<tr>
				<td style="width: 20%;font-size: 18px"></td>
				<td style="width: 20%">&nbsp;</td> 
				<td style="width: 40%;text-align:center" align="center">
				@php 
				$image  =\Storage_path('/app'.$polldatetime[0]->signature);
				@endphp
					<img src="{{ $image }}" alt="" height="50px" align="center">
				</td>
			</tr>
			<tr>
				<td style="font-size: 14px">&nbsp;Date : {{date('d-m-Y')}}</td>
				<td>&nbsp;</td> 
				<td style="font-size: 14px" align="center">{{ $blockMcs[0]->stamp_l1 }}<br>{{ $blockMcs[0]->stamp_l2 }}</td>
			</tr>
		</tbody>
	</table> 
	<hr style="height:2px;border-width:0;color:black;background-color:black;margin-top:0px">
	@php
		$counter++;
		if($counter==2){$counter=0;
	@endphp	

		<pagebreak>

	@php
		}

	@endphp 
@endforeach
