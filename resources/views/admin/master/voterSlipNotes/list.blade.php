<table class="table table-striped table-bordered">
	<thead>
		<tr>
			<th>Note Sr.No.</th>
			<th>Notes</th>
			<th>Action</th>
			
		</tr>
	</thead>
	<tbody>
		@foreach ($voterSlipNotes as $voterSlipNote)
					
		<tr>
			<td>{{ $voterSlipNote->note_srno }}</td>
			<td>{{ $voterSlipNote->note_text }}</td>
			
			<td>
				<a onclick="callPopupLarge(this,'{{ route('admin.Master.voter.slip.notes.edit',$voterSlipNote->id) }}')" title="Edit" class="btn btn-info btn-xs"><i class="fa fa-edit"></i></a>
                                             
				<a class="btn btn-xs btn-danger" select-triger="district_select_box" success-popup="true" onclick="callAjax(this,'{{ route('admin.Master.voter.slip.notes.delete',$voterSlipNote->id) }}')"><i class="fa fa-trash" style="color: #fff"></i></a>
			</td>
			
		</tr> 
		@endforeach
	</tbody>
</table>