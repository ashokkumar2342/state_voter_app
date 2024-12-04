@foreach ($booths as $booth)
	<tr>
		<td>{{ $booth->booth_no }}{{ $booth->booth_no_c }}</td>
		<td>{{ $booth->fromsrno }}</td>
		<td>{{ $booth->tosrno }}</td>
		<td>
			<a href="#" title="Edit" class="btn btn-info btn-xs" onclick="callPopupLarge(this,'{{ route('admin.Master.MappingWardBoothEdit',$booth->id) }}')"><i class="fa fa-edit"></i></a>
			 
		</td>
	</tr> 
@endforeach