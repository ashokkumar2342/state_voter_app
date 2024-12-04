 
<div class="col-lg-12 table-responsive"> 
<table class="table" id="village_ward_sample_datarecordtable">
	<thead>
		<tr>
			<th>state_id</th>
			<th>district_id</th>
			<th>block_id</th>
			<th>village_id</th>
			<th>state_name</th>
			<th>district_name</th>
			<th>block_name</th>
			<th>village_name</th>
			<th>total_ward</th>
			<th>zp_ward</th>
			<th>ps_ward</th>
			<th>save_status</th>
			<th>status_zp</th>
			<th>status_ps</th>
		</tr>
	</thead>
	<tbody>
		@foreach ($villageSamples as $villageSample)
		<tr>
			<td>{{ $villageSample->state_id }}</td>
			<td>{{ $villageSample->district_id }}</td>
			<td>{{ $villageSample->block_id }}</td>
			<td>{{ $villageSample->village_id }}</td>
			<td>{{ $villageSample->state_name }}</td>
			<td>{{ $villageSample->district_name }}</td>
			<td>{{ $villageSample->block_name }}</td>
			<td>{{ $villageSample->village_name }}</td>
			<td>{{ $villageSample->total_ward }}</td>
			<td>{{ $villageSample->zp_ward }}</td>
			<td>{{ $villageSample->ps_ward }}</td>
			<td>{{ $villageSample->save_status }}</td>
			<td>{{ $villageSample->status_zp }}</td>
			<td>{{ $villageSample->status_ps }}</td>
			 
		</tr> 
		@endforeach
	</tbody>
</table>
</div>
 
