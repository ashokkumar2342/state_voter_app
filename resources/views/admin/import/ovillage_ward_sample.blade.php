<div class="row"> 
<div class="col-lg-12 table-responsive"> 
    <table class="table" id="village_ward_sample_table">
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
          @foreach ($villagewards as $villageward)
          <tr>
            <td>{{ $villageward->state_id }}</td>
            <td>{{ $villageward->state_name }}</td>
            <td>{{ $villageward->district_id }}</td>
            <td>{{ $villageward->district_name }}</td>
            <td>{{ $villageward->block_id }}</td>
            <td>{{ $villageward->block_name }}</td>
            <td>{{ $villageward->village_id }}</td>
            <td>{{ $villageward->village_name }}</td>
            <td>{{ @$villageward->Total_Wards }}</td>
            <td></td>
            <td></td>
          </tr> 
          @endforeach
        </tbody>
        </table>
</div>
</div> 