<div class="col-lg-12">
    <fieldset class="fieldset_border">
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover">
                <thead class="thead-dark">
                    <tr> 
                        <th>Sr. No.</th>
                        <th>Polling Booth No.</th>
                        <th>From Sr.No.</th>
                        <th>To Sr.No.</th>
                        <th>Action</th>
                    </tr>
                </thead>
                @php
                    $srno = 1;
                @endphp
                <tbody> 
                    @foreach ($rs_booths as $rs_val)
                        <tr>
                            <td>{{ $srno++ }}</td>
                            <td>{{ $rs_val->booth_no }}{{ $rs_val->booth_no_c }}</td>
                            <td>{{ $rs_val->fromsrno }}</td>
                            <td>{{ $rs_val->tosrno }}</td>
                            <td>
                                <button type="button" class="btn btn-info btn-sm" onclick="callPopupLarge(this,'{{ route('admin.Master.MappingWardBoothEdit', Crypt::encrypt($rs_val->id)) }}')"><i class="fa fa-edit"></i> Edit</button>
                            </td>
                        </tr> 
                    @endforeach
                </tbody>
            </table>
        </div>
    </fieldset>
</div>