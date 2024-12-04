<table id="zp_ward_datatable" class="table table-striped table-hover control-label">
     <thead>
         <tr>
             <th>Ward No.</th>
             <th>Ward Name(English)</th>
             <th>Ward Name(Hindi)</th>
             <th>Action</th>
         </tr>
     </thead>
     <tbody>
     	@foreach ($ZilaParishads as $ZilaParishad)
     	<tr>
     		<td>{{ $ZilaParishad->ward_no }}</td>
     		<td>{{ $ZilaParishad->name_e }}</td>
     		<td>{{ $ZilaParishad->name_l }}</td>
            <td>
                <a href="#" class="btn btn-xs btn-info" onclick="callPopupLarge(this,'{{ route('admin.Master.ZilaParishadEdit',$ZilaParishad->id) }}')"><i class="fa fa-edit"></i></a>
                <a href="#" class="btn btn-xs btn-danger" success-popup="true" select-triger="district_select_box" onclick="callAjax(this,'{{ route('admin.Master.ZilaParishadDelete',$ZilaParishad->id) }}')"><i class="fa fa-trash"></i></a>
            </td>
     	</tr>	 
     	@endforeach 
     </tbody>
 </table>