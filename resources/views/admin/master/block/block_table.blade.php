<div class="col-lg-12">
    <fieldset class="fieldset_border">
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover" id="ajax_data_table">
                <thead class="thead-dark">
                    <tr> 
                        <th>Sr. No.</th>
                        <th>Code</th>
                        <th>Name (English)</th>
                        <th>Name (Hindi)</th>
                        <th>Block / MC's Type</th>
                        <th>Total P.S. Ward</th>
                        <th>Action</th>
                    </tr>
                </thead>
                @php
                    $srno = 1;
                @endphp
                <tbody> 
                    @foreach ($rs_blocks as $rs_val)
                        <tr>
                            <td>{{ $srno++ }}</td>
                            <td>{{ $rs_val->code }}</td>
                            <td>{{ $rs_val->name_e }}</td>
                            <td>{{ $rs_val->name_l }}</td>
                            <td>{{ $rs_val->block_mc_type_e}}</td>
                            <td>{{ $rs_val->pscount}}</td>
                            <td class="text-nowrap">
                                @if ($rs_val->block_mc_type_id == 1)
                                    <button type="button" onclick="callPopupLarge(this,'{{ route('admin.master.block.mcs.psWard', Crypt::encrypt($rs_val->id)) }}')" title="" class="btn btn-primary btn-sm">Add P.S Ward</button>
                                @endif

                                <button type="button" select2="true" onclick="callPopupLarge(this,'{{ route('admin.master.block.mcs.edit', Crypt::encrypt($rs_val->id)) }}')" title="" class="btn btn-info btn-sm"><i class="fa fa-edit"></i> Edit</button>
                                @if ($role_id == 1)
                                    <button type="button" success-popup="true" select-triger="district_select_box" onclick="if(confirm('Are you sure you want to delete this record?')==true){callAjax(this,'{{ route('admin.master.block.mcs.delete', Crypt::encrypt($rs_val->id)) }}')}" title="" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i> Delete</button>
                                @endif
                            </td>
                        </tr> 
                    @endforeach
                </tbody>
            </table>
        </div>
    </fieldset>
</div>