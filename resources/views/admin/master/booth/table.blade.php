<div class="card card-primary table-responsive"> 
     <table id="ward_datatable" class="table table-striped table-hover control-label">
         <thead>
             <tr>
                 <th class="text-nowrap">Booth No.</th> 
                 <th class="text-nowrap">Booth Name (Eng.)</th>
                 <th class="text-nowrap">Booth Name (Hin.)</th>
                 <th class="text-nowrap">Action</th> 
             </tr>
         </thead>
         <tbody>
            @foreach ($booths as $booth)
             <tr>
                 <td>{{ $booth->booth_no }}{{ $booth->booth_no_c }}</td>
                  
                 <td>{{ $booth->name_e }}</td>
                 <td>{{ $booth->name_l }}</td>
                 <td>
                     <a href="#" class="btn btn-xs btn-info" onclick="callPopupLarge(this,'{{ route('admin.Master.boothEdit',$booth->id) }}')"><i class="fa fa-edit"></i></a>
                     <a href="#" class="btn btn-xs btn-danger" select-triger="village_select_box" success-popup="true" onclick="callAjax(this,'{{ route('admin.Master.boothDelete',$booth->id) }}')"><i class="fa fa-trash"></i></a>
                 </td>
                 
             </tr> 
            @endforeach
         </tbody>
     </table>
</div> 