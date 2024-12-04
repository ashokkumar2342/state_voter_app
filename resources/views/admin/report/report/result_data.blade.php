<div class="row"> 
<div class="col-lg-12 table-responsive"> 
    <table class="table" id="village_ward_sample_table" width = "100%">
        <thead>
          <tr>
            @php
              $countr = 0;
              while ($countr < $tcols ){
                @endphp
                <th width = "{{ $qcols[$countr][1] }}%">{{ $qcols[$countr][0] }}</th>
                @php
                $countr = $countr+1;
              }
            @endphp
          </tr>
        </thead>
        <tbody>
          @foreach ($rs_result as $rs_row)
          <tr>
            @foreach ($rs_row as $value)
              <td>{{ $value }}</td>  
            @endforeach
          </tr> 
          @endforeach
        </tbody>
      
    </table>
</div>
</div> 