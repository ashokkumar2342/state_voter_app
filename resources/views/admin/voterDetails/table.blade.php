<div class="col-lg-12">
    <fieldset class="fieldset_border">
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover">
                <thead class="thead-dark">
                    <tr> 
                        <th>Sr. No.</th>
                        <th>Name </th>
                        <th>F/H Name</th>
                        <th>Epic No.</th>
                        <th>Ward No.</th>
                        <th>Booth No.</th>
                    </tr>
                </thead>
                <tbody> 
                    @foreach ($rs_voterLists as $rs_val)
                        <tr>
                            <td>{{ $rs_val->sr_no }}</td>
                            <td>{{ $rs_val->name_e }}</td>
                            <td>{{ $rs_val->father_name_e }}</td>
                            <td>{{ $rs_val->voter_card_no }}</td>
                            <td>{{ $rs_val->ward_no }}</td>
                            <td>{{ $rs_val->booth_no }}</td>
                            {{-- <td>
                            	
	                            <a  onclick="callPopupLarge(this,'{{ route('admin.voter.voteredit',$voterlist->id) }}')" title="Edit" class="btn btn-xs btn-info"><i class="fa fa-edit"></i></a>
								<a success-popup="true" select-triger="village_select_box" onclick="callAjax(this,'{{ route('admin.voter.voterDelete',$voterlist->id) }}')" title="Delete" class="btn btn-xs btn-danger"><i class="fa fa-trash"></i></a> 
                            </td> --}}
                        </tr> 
                    @endforeach
                </tbody>
            </table>
        </div>
    </fieldset>
</div>