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
                        <th>Voter Card No.</th>
                        <th>Name</th>
                        <th>Father Name</th>
                        <th>Deleted From Ward No.</th>
                        <th>Ward No.</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody> 
                    @foreach ($results as $result)
                        <tr>
                            <td>{{ $result->sr_no}}</td>
                            <td>{{ $result->voter_card_no}}</td>
                            <td>{{ $result->name_e }}</td>
                            <td>{{ $result->father_name_e }}</td>
                            <td>{{ $result->from_vil_ward }}</td>
                            <td>{{ $result->vil_ward }}</td>
                            <td>
                                <button type="button" class="btn btn-info btn-sm" @if ($refreshdata == 1) select-triger="assembly_part_select_box"  @endif success-popup="true" onclick="callAjax(this,'{{ route('admin.Master.change.voter.with.ward.restore',[Crypt::encrypt($result->id), Crypt::encrypt($result->ward_id)]) }}')">Restore</button>
                            </td>
                        </tr> 
                    @endforeach
                </tbody>
            </table>
        </div>
    </fieldset>
</div>