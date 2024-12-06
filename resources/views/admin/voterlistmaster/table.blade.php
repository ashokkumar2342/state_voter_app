<div class="col-lg-12">
    <fieldset class="fieldset_border">
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover" id="ajax_data_table">
                <thead class="thead-dark">
                    <tr> 
                        <th>Sr. No.</th>
                        <th>Voter List Name</th>
                        <th>Voter List Type</th>
                        <th>Year Publication</th>
                        <th>Date Publication</th>
                        <th>Year Base</th>
                        <th>Date Base</th>
                        <th>Remarks</th>
                        <th>Action</th>
                    </tr>
                </thead>
                @php
                    $srno = 1;
                @endphp
                <tbody> 
                    @foreach ($rs_voter_list_master as $rs_val)
                        <tr class="{{ $rs_val->status==1?'bg-success':'' }}">
                            <td>{{ $srno++ }}</td>
                            <td>{{ $rs_val->voter_list_name }}</td>
                            <td>{{ $rs_val->voter_list_type }}</td>
                            <td>{{ $rs_val->year_publication }}</td>
                            <td>{{ $rs_val->date_publication}}</td>
                            <td>{{ $rs_val->year_base}}</td>
                            <td>{{ $rs_val->date_base}}</td>
                            <td>{{ $rs_val->remarks1}}</td>
                            <td class="text-nowrap">
                                @if ($rs_val->status==1)
                                    {{-- <button type="button" select-triger="block_select_box" success-popup="true" onclick="callAjax(this,'{{ route('admin.VoterListMaster.default', Crypt::encrypt($rs_val->id)) }}')" class="btn btn-success btn-sm">Default</button> --}}
                                @else
                                    <button type="button" select-triger="block_select_box" success-popup="true" onclick="callAjax(this,'{{ route('admin.VoterListMaster.default', Crypt::encrypt($rs_val->id)) }}')" title="" class="btn btn-default btn-sm">Default</button> 
                                @endif
                                <button type="button" class="btn btn-sm btn-info" onclick="callPopupLarge(this,'{{ route('admin.VoterListMaster.edit', Crypt::encrypt($rs_val->id)) }}')"><i class="fa fa-edit"></i> Edit</button>   
                            </td>
                        </tr> 
                    @endforeach
                </tbody>
            </table>
        </div>
    </fieldset>
</div>