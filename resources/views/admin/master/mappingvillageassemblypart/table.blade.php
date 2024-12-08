<div class="col-lg-12">
    <fieldset class="fieldset_border">
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover" id="ajax_data_table">
                <thead class="thead-dark">
                    <tr> 
                        <th>Sr. No.</th>
                        <th>Assembly Code</th>
                        <th>Part No.</th>
                        <th>Action</th>
                    </tr>
                </thead>
                @php
                    $srno = 1;
                @endphp
                <tbody> 
                    @foreach ($rs_records as $rs_val)
                        <tr>
                            <td>{{ $srno++ }}</td>
                            <td>{{ $rs_val->code }}</td>
                            <td>{{ $rs_val->part_no }}</td>
                            <td class="text-nowrap">
                                <button type="button" onclick="callAjax(this,'{{ route('admin.Master.MappingVillageAssemblyPartRemove', Crypt::encrypt($rs_val->id)) }}')" class="btn btn-sm btn-danger" select-triger="village_select_box" success-popup="true"><i class="fa fa-delete"></i> Delete</button>    
                            </td>
                        </tr> 
                    @endforeach
                </tbody>
            </table>
        </div>
    </fieldset>
</div>