<table id="district_datatable" class="table table-striped table-hover control-label">
    <thead>
        <tr>
            <th>Code</th>
            <th>Name (English)</th>
            <th>Name (Hindi)</th>
            <th>Total Z.P. Ward</th>
            <th>Action</th>
             
        </tr>
    </thead>
    <tbody> 
@foreach ($Districts as $District)
@php
    $ZilaParishad = App\Helper\MyFuncs::ZPWard_Count($District->id);
@endphp
 <tr>
     <td>{{ $District->code }}</td>
     <td>{{ $District->name_e }}</td>
     <td>{{ $District->name_l }}</td>
     <td>{{ $ZilaParishad }}</td>
     <td class="text-nowrap">
         <a onclick="callPopupLarge(this,'{{ route('admin.Master.DistrictsZpWard',$District->id) }}')" title="" class="btn btn-primary btn-xs" style="color: #fff">Add Z.P. Ward</a>
         <a onclick="callPopupLarge(this,'{{ route('admin.Master.districtsEdit',$District->id) }}')" title="" class="btn btn-info btn-xs"><i class="fa fa-edit"></i></a>
         <a success-popup="true" select-triger="state_select_box" onclick="if(confirm('Are you sure you want to delete this item?')==true){callAjax(this,'{{ route('admin.Master.districtsDelete',Crypt::encrypt($District->id)) }}')}" title="" class="btn btn-danger btn-xs"><i class="fa fa-trash"></i></a>
     </td>
 </tr> 
@endforeach
</tbody>
</table>