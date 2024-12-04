<div class="row"> 
<div class="col-lg-12 table-responsive"> 
    <table class="table table-striped" id="assembly_sample_table">
       <thead>
           <tr>
               <th>district_name</th>
               <th>district_id</th>
               <th>assembly_code</th>
               <th>assembly_name_eng</th>
               <th>assembly_name_hindi</th>
               <th>total_parts</th>
           </tr>
       </thead>
       <tbody>
        @foreach ($assemblys as $assembly) 
           <tr>
               <td>{{ $assembly->name_e }}</td>
               <td>{{ $assembly->id }}</td>
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