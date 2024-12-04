<div class="card card-primary table-responsive"> 
     <table id="ward_datatable" class="table table-striped table-hover control-label">
         <thead>
             <tr>
                 <th class="text-nowrap">Ward No.</th>
                 <th class="text-nowrap">Ward Name (In English)</th>
                 <th class="text-nowrap">Ward Name (In Hindi)</th>
                 <th>Action</th> 
                  
             </tr>
         </thead>
         <tbody>
            @foreach ($wards as $ward)
             <tr>
                 <td>{{ $ward->ward_no }}</td>
                 <td>{{ $ward->name_e }}</td>
                 <td>{{ $ward->name_l }}</td>
                 <td>
                    <a href="#" class="btn btn-xs btn-danger" success-popup="true" select-triger="village_select_box" onclick="callAjax(this,'{{ route('admin.Master.VillageWardDelete',$ward->id) }}')"><i class="fa fa-trash"></i></a>
                </td>
             </tr> 
            @endforeach
         </tbody>
     </table>
</div> 