<div class="col-lg-12"> 
  <div class="card card-primary">
  <div class="card-header">
     <h3 class="card-title"></h3>
    </div> 
    <div class="card-body">
       <table class="table">
         <thead>
           <tr>
             <th>Assembly Code</th>
             <th>Part No.</th>
             <th>Action</th>
           </tr>
         </thead>
         <tbody>
          @foreach ($assemblyParts as $assemblyPart)
           <tr>
             <td>{{ $assemblyPart->code}}</td>
             <td>{{ $assemblyPart->part_no }}</td>
             <td class="text-center">
               <a onclick="callAjax(this,'{{ route('admin.Master.MappingVillageAssemblyPartRemove',$assemblyPart->id) }}')" title="Remove" class="btn" select-triger="village_select_box" success-popup="true"><i class="fa fa-remove text-danger"></i></a>
             </td>
           </tr> 
          @endforeach
         </tbody>
       </table>
    </div>
  </div>
</div>

