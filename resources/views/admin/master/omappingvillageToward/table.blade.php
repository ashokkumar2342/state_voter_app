<div class="row">
<div class="col-lg-12 table-responsive"> 
<table class="table" id="village_sample_table">
	<thead>
		<tr>
			<th>state_id</th>
			<th>state_name</th>
			<th>district_id</th>
			<th>district_name</th>
			<th>block_id</th>
			<th>block_name</th>
			<th>village_id</th>
			<th>village_name</th>
			<th>total_wards</th>
			<th>zp_ward_no</th>
			<th>ps_ward_noo</th>
		</tr>
	</thead>
	<tbody>
		@foreach ($villageSamples as $villageSample)
		<tr>
			<td>{{ $villageSample->state_id }}</td>
			<td>{{ $villageSample->state_name }}</td>
			<td>{{ $villageSample->district_id }}</td>
			<td>{{ $villageSample->district_name }}</td>
			<td>{{ $villageSample->block_id }}</td>
			<td>{{ $villageSample->block_name }}</td>
			<td>{{ $villageSample->village_id }}</td>
			<td>{{ $villageSample->village_name }}</td>
			<td>{{ $villageSample->Total_Wards }}</td>
			<td></td>
			<td></td>
		</tr> 
		@endforeach
	</tbody>
</table>
</div>
</div>
