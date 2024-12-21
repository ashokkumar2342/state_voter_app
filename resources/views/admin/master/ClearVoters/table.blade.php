<div class="col-lg-12">
    <fieldset class="fieldset_border">
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover" id="ajax_data_table">
                <thead class="thead-dark">
                    <tr> 
                        <th>Booth No.</th>
                        <th>Ward No.</th>
                        <th>Total Votes</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody> 
                    @foreach ($rs_result as $rs_val)
                        <tr>
                            <td>{{ $rs_val->booth_no }}</td>
                            <td>{{ $rs_val->ward_no }}</td>
                            <td>{{ $rs_val->total_votes }}</td>
                            <td class="text-nowrap">
                                <button type="button" success-popup="true" select-triger="village_select_box" onclick="if(confirm('Are you sure you want to Clear this record?')==true){callAjax(this,'{{ route('admin.clear.voters.clear', Crypt::encrypt($rs_val->pb_booth_id.':'.$rs_val->wv_ward_id)) }}')}" title="" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i> Clear</button>
                                <button type="button"  select2="true" class="btn btn-info btn-sm" onclick="callPopupLarge(this, '{{ route('admin.clear.voters.shift', Crypt::encrypt($rs_val->pb_booth_id.':'.$rs_val->wv_ward_id)) }}')"> Shift To Other Booth</button>
                            </td>
                        </tr> 
                    @endforeach
                </tbody>
            </table>
        </div>
    </fieldset>
</div>