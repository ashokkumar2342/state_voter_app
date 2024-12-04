<div class="col-lg-12"> 
<table class="table-striped table-bordered table" id="voterlistdatatable">
	<thead>
		<tr>
			<th>Name</th>
			<th>F/H Name</th>
			<th>Action</th> 
		</tr>
	</thead>
	<tbody>
		@foreach ($voterlists as $voterlist)
		<tr>
			<td>{{ $voterlist->name_e }}</td>
			<td>{{ $voterlist->father_name_e }}</td> 
			<td>
			{{-- <a  onclick="callPopupLarge(this,'{{ route('admin.voter.voteredit',$voterlist->id) }}')" title="Edit" class="btn btn-xs btn-info"><i class="fa fa-edit"></i></a>
			<a success-popup="true" select-triger="village_select_box" onclick="callAjax(this,'{{ route('admin.voter.voterDelete',$voterlist->id) }}')" title="Delete" class="btn btn-xs btn-danger"><i class="fa fa-trash"></i></a>  --}}
			</td> 
		</tr> 
		@endforeach
	</tbody>
</table>
</div>