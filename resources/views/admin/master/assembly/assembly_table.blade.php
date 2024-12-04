<table id="district_table" class="table table-striped table-hover control-label">
<thead>
<tr> 
<th>Code</th>
<th>Name (English)</th>
<th>Name (Hindi)</th>
<th>Total Part</th>
<th>Action</th>

</tr>
</thead>
<tbody> 
@foreach ($assemblys as $assembly)
<tr>
<td>{{ $assembly->code }}</td>
<td>{{ $assembly->name_e }}</td>
<td>{{ $assembly->name_l }}</td>
<td>{{ $assembly->tcount }}</td>
<td class="text-nowrap">
<a onclick="callPopupLarge(this,'{{ route('admin.Master.AssemblyPart.edit',$assembly->id) }}')" title="" class="btn btn-primary btn-xs" style="color: #fff">Add Part</a>
<a onclick="callPopupLarge(this,'{{ route('admin.Master.Assembly.edit',$assembly->id) }}')" title="" class="btn btn-info btn-xs"><i class="fa fa-edit"></i></a>
<a success-popup="true" select-triger="district_select_box" onclick="if(confirm('Are you sure you want to delete this item?')==true){callAjax(this,'{{ route('admin.Master.Assembly.delete',$assembly->id) }}')}" title="Delete" class="btn btn-danger btn-xs"><i class="fa fa-trash"></i></a>
</td>
</tr> 
@endforeach
</tbody>
</table>