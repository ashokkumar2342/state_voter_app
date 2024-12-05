<div class="col-lg-12">
    <fieldset class="fieldset_border">
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover" id="ajax_data_table">
                <thead class="thead-dark">
                    <tr> 
                        <th>Sr. No.</th>
                        <th>Ward No.</th>
                        <th>Ward Name (English)</th>
                        <th>Ward Name (Hindi)</th>
                        <th>Action</th>
                    </tr>
                </thead>
                @php
                    $srno = 1;
                @endphp
                <tbody> 
                    @foreach ($rs_wards as $rs_val)
                        <tr>
                            <td>{{ $srno++ }}</td>
                            <td>{{ $rs_val->ward_no }}</td>
                            <td>{{ $rs_val->name_e }}</td>
                            <td>{{ $rs_val->name_l }}</td>
                            <td class="text-nowrap">
                                @if ($role_id == 1)
                                    <button type="button" success-popup="true" select-triger="village_select_box" onclick="if(confirm('Are you sure you want to delete this record?')==true){callAjax(this,'{{ route('admin.Master.ward.delete', Crypt::encrypt($rs_val->id)) }}')}" title="" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i> Delete</button>
                                @endif    
                            </td>
                        </tr> 
                    @endforeach
                </tbody>
            </table>
        </div>
    </fieldset>
</div>