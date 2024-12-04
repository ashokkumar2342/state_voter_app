<div class="row"> 
<div class="col-lg-12 table-responsive"> 
    <table class="table table-striped" id="block_sample_table">
        <thead>
           <tr>
               <th>district_name</th>
               <th>district_id</th>
               <th>block_code</th>
               <th>block_name_eng</th>
               <th>block_name_hindi</th>
               <th>block_mc_type_id</th>
               <th>total_wards</th>
           </tr>
        </thead>
        <tbody>
        @foreach ($blocks as $block) 
           <tr>
               <td>{{ $block->name_e }}</td>
               <td>{{ $block->id }}</td>
               <td></td>
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