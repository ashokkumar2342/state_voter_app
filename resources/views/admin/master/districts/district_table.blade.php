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
                        <th>Total Z.P. Ward</th>
                        <th>Action</th>
                    </tr>
                </thead>
                @php
                    $srno = 1;
                @endphp
                <tbody> 
                    @foreach ($rs_district as $rs_val)
                    @php
                        $ZilaParishad = App\Helper\MyFuncs::ZPWard_Count($rs_val->id);
                    @endphp
                        <tr>
                            <td>{{ $srno++ }}</td>
                            <td>{{ $rs_val->code }}</td>
                            <td>{{ $rs_val->name_e }}</td>
                            <td>{{ $rs_val->name_l }}</td>
                            <td>{{ $ZilaParishad }}</td>
                            <td class="text-nowrap">
                                <button type="button" onclick="callPopupLarge(this,'{{ route('admin.Master.districts.zpWard', Crypt::encrypt($rs_val->id)) }}')" class="btn btn-primary btn-sm">Add Z.P. Ward</button>

                                <button type="button" select2="true" onclick="callPopupLarge(this,'{{ route('admin.Master.districts.edit', Crypt::encrypt($rs_val->id)) }}')" class="btn btn-info btn-sm"><i class="fa fa-edit"></i> Edit</button>
                            </td>
                        </tr> 
                    @endforeach
                </tbody>
            </table>
        </div>
    </fieldset>
</div>