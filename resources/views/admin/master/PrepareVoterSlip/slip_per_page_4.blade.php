@php
	$counter = 0;
@endphp
@foreach ($voterReports as $voterReport) 
	<table style="border-collapse: collapse; width: 100%;margin-top:5px" border="1" >
		<tbody>
			<tr>
				<td style="width: 100%;">
						<table width="100%">
							<tbody>
								<tr>
									<td style="text-align:center;font-size:10px;word-spacing:5px"><b>{{$slipheader}}</b></td>
								</tr>
							</tbody>
						</table>
						<table width="100%">
							<tbody>
								<tr>
									<td style="width: 100%; text-align:center;font-size:10px;word-spacing:5px"><b>Voter Slip</b></td>
								</tr>
							</tbody>
						</table>
						<table width="100%">
							<tbody>
								<tr style="height: 13px;">
									<td style="width: 80px; height: 13px;font-size:10px;word-spacing:5px;">वार्ड न० :</td>
									<td style="width: 74px; height: 13px;font-size:10px;word-spacing:5px"><b>{{$wardno}}</b></td>
									<td style="width: 93px; height: 13px;font-size:10px;word-spacing:5px">Part No. :</td>
									<td style="width: 249px; height: 13px;font-size:10px;word-spacing:5px"><b>{{$voterReport->part_no}}</b></td>
									<td style="width: 91px; height: 67px;" rowspan="4">
									@php	
									$dirpath = '/app/vimage/'.$voterReport->data_list_id.'/'.$voterReport->assembly_id.'/'.$voterReport->assembly_part_id;
									$name =$voterReport->sr_no;
									$image  =\Storage_path($dirpath.'/'.$name.'.jpg');
									@endphp
										<img src="{{ $image }}" alt="" width="130px" height="130px">
									</td>
								</tr>
								<tr>
									<td style="font-size:10px;word-spacing:5px">नाम</td>
									<td style="font-size:10px;word-spacing:5px" colspan="3"><b>{{ $voterReport->name_l }}&nbsp;</b></td>
								</tr>
								<tr>
									<td style="font-size:10px;word-spacing:5px">लिंग</td>
									<td style="font-size:10px;word-spacing:5px"><b>{{$voterReport->genders_l}}</b></td>
									<td style="font-size:10px;word-spacing:5px">EPIC No. :</td>
									<td style="font-size:10px;word-spacing:5px"><b>{{ $voterReport->voter_card_no }}</b></td>
								</tr>
								<tr>
									<td style="font-size:10px;word-spacing:5px">{{ $voterReport->vrelation }}</td>
									<td style="font-size:10px;word-spacing:5px" colspan="3"><b>{{ $voterReport->father_name_l }}&nbsp;</b></td>
								</tr>
							</tbody>
						</table>
						<table width="100%">
							<tbody>
								<tr>
									<td style="width: 100%; font-size:10px;word-spacing:5px">मतदाता क्रमांक संख्या : <b>{{ $voterReport->print_sr_no }}</b></td>
								</tr>
								<tr>
									<td style="font-size:9px;word-spacing:5px">Polling Station No. and Name : <b>{{ $voterReport->boothno }} -  {{ $voterReport->pb_name }}</b> </td>
								</tr>
								<tr>
									<td style="font-size:9px;word-spacing:5px">Poll Date, Day and Time : <b>{{ $polldatetime[0]->polling_day_time_l }}</b></td>
								</tr>
								@foreach ($slipNotes as $val_slip_note) 
									<tr>
										<td style="font-size:8px">Note: {{$val_slip_note->note_srno}} {{$val_slip_note->note_text}}</td>
									</tr>
								@endforeach
							</tbody>
						</table>
						<table style="width: 100%;">
							<tbody>
								<tr>
									<td style="width: 20%;font-size: 8px"></td>
									<td style="width: 20%">&nbsp;</td> 
									<td style="width: 40%;text-align:center" align="center">
									@php 
									$image  =\Storage_path('/app'.$polldatetime[0]->signature);
									@endphp
										<img src="{{ $image }}" alt="" height="50px" align="center">
									</td>
								</tr>
								<tr>
									<td style="font-size: 8px">&nbsp;Date : {{date('d-m-Y')}}</td>
									<td>&nbsp;</td> 
									<td style="font-size: 8px" align="center">{{ $blockMcs[0]->stamp_l1 }}<br>{{ $blockMcs[0]->stamp_l2 }}</td>
								</tr>
							</tbody>
						</table>
				</td>
				
			</tr>
		</tbody>
	</table>
	<hr style="height:2px;border-width:0;color:black;background-color:black;margin-top:0px">
	@php
		$counter++;
		if($counter==4){$counter=0;
	@endphp	

		<pagebreak>

	@php
		}

	@endphp
@endforeach