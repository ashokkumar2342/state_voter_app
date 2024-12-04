<table id="part_no_datatable" class="table table-striped table-hover control-label">
<thead>
<tr> 
<th>Part No.</th> 
<th>Action</th> 
</tr>
</thead>
<tbody> 
@foreach ($assemblyParts as $assemblyPart)
<tr>  
<td>{{ $assemblyPart->part_no }}</td> 
<td class="text-nowrap">
<a href="{{ route('admin.Master.AssemblyPart.delete',$assemblyPart->id) }}" title="" class="btn btn-danger btn-xs"><i class="fa fa-trash"></i></a>
</td>
</tr> 
@endforeach
</tbody>
</table>