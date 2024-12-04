<div class="row"> 
<div class="col-lg-12 table-responsive"> 
    <table class="table table-striped" id="village_sample_table">
		<thead>
		   <tr>
		       <th>state_name</th>
		       <th>state_Id</th>
		       <th>district_name</th>
		       <th>district_id</th>
		       <th>block_name</th>
		       <th>block_id</th>
		       <th>village_code</th>
		       <th>village_name_eng</th>
		       <th>village_name_hindi</th>
		       <th>total_wards</th>
		   </tr>
		</thead>
		<tbody>
		@foreach ($villages as $villages) 
		   <tr>
		       <td>{{ $villages->state_name }}</td>
		       <td>{{ $villages->state_id }}</td>
		       <td>{{ $villages->district_name }}</td>
		       <td>{{ $villages->district_id }}</td>
		       <td>{{ $villages->block_name }}</td>
		       <td>{{ $villages->block_id }}</td>
		       <td></td>
		       <td></td>
		       <td></td>
		       <td></td>
		   </tr>
		@endforeach
		</tbody>
</div>
</div> 