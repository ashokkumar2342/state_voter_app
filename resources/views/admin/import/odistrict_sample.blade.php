<div class="row"> 
<div class="col-lg-12 table-responsive"> 
    <table class="table table-striped" id="district_sample_table">
        <thead>
            <tr>
                <th>state_name</th>
                <th>state_id</th>
                <th>district_code</th>
                <th>district_name_eng</th>
                <th>district_name_hindi</th>
                <th>total_zp_wards</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($Districts as $District) 
            <tr>
                <td>{{ $District->name_e }}</td>
                <td>{{ $District->id }}</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
</div> 