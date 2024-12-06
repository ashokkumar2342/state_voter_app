<div class="col-lg-12 text-right">
    <button type="button" class="btn btn-default pull-right" onclick="$('#assembly_part_select_box').trigger('change');" style="margin:5px;background: #6c757d;color: #fff;"><i class="fa fa-refresh"></i> Refresh</button>
</div>
<div class="col-lg-12">
    <fieldset class="fieldset_border">
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover" id="ajax_data_table">
                <thead class="thead-dark">
                    <tr> 
                        <th>Sr. No.</th>
                        <th>EPIC No.</th>
                        <th>Name</th>
                        <th>F/H Name</th>
                        <th>Village</th>
                        <th>Ward</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody> 
                    @foreach ($rs_voterLists as $rs_val)
                        <tr>
                            <td>{{ $rs_val->sr_no }}</td>
                            <td>{{ $rs_val->voter_card_no }}</td>
                            <td>{{ $rs_val->name_e }}</td>
                            <td>{{ $rs_val->father_name_l }}</td>
                            <td>{{ $rs_val->vil_name }}</td>
                            <td>{{ $rs_val->ward_no }}</td>
                            <td>
                                @if($rs_val->village_id > 0)
                                    <button type="button" @if($refreshdata == 1)select-triger="assembly_part_select_box" @endif onclick="callAjax(this,'{{ route('admin.Master.removeVoter_wardbandi', Crypt::encrypt($rs_val->id)) }}')" success-popup="true" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i> Delete</a>
                                @endif  
                            </td>
                        </tr> 
                    @endforeach
                </tbody>
            </table>
        </div>
    </fieldset>
</div>