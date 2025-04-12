<div class="col-lg-12 text-right">
    <button type="button" class="btn btn-default pull-right" onclick="$('#voter_list_master_id').trigger('change');" style="margin:5px;background: #6c757d;color: #fff;"><i class="fa fa-refresh"></i> Refresh</button>
</div>
<div class="col-lg-12">
    <fieldset class="fieldset_border">
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover" id="ajax_data_table">
                <thead class="thead-dark">
                    <tr> 
                        <th>Sr. No.</th>
                        <th>Village</th>
                        <th>Ward</th> 
                        <th>Booth</th> 
                        <th>Report Type</th>
                        <th>Submit Time</th>
                        <th>Expected Time to Process</th>
                        <th>Download With Photo</th>
                        <th>Download Without Photo</th>
                        <th>Download Annexure-A</th>
                    </tr>
                </thead>
                @php
                    $srno = 1;
                    $btn_counter = 1;
                @endphp
                <tbody> 
                    @foreach ($voterlistprocesseds as $voterlistprocessed)
                        <tr> 
                            <td>{{ $srno++}}</td>
                            <td>{{ $voterlistprocessed->name_e}}</td>
                            <td>{{ $voterlistprocessed->ward_no}}</td> 
                            <td>{{ $voterlistprocessed->booth_no}}</td> 
                            <td>{{ $voterlistprocessed->report_type}}</td>
                            <td>{{ $voterlistprocessed->submit_time}}</td>
                            <td>{{ $voterlistprocessed->expected_time_start}}</td>
                            @if ($voterlistprocessed->status==1)
                                <td>
                                    <a type="button" target="_blank" class="btn btn-sm btn-success" href="{{ route('admin.voter.download.captcha',[Crypt::encrypt($voterlistprocessed->id),'p']) }}">Download</a>
                                </td>
                                <td>
                                    <a type="button" target="_blank" class="btn btn-sm btn-success" href="{{ route('admin.voter.download.captcha',[Crypt::encrypt($voterlistprocessed->id),'w']) }}">Download</a>
                                </td>
                                <td><a type="button" target="_blank" href="{{ route('admin.voter.VoterListDownloadPDFH',[Crypt::encrypt($voterlistprocessed->id),'h']) }}" title="">Download</a></td>
                            @elseif ($voterlistprocessed->status==2)
                                <td>Processing</td>
                                <td>Processing</td>
                                <td>Processing</td>
                            @else
                                <td>Pending</td>
                                <td>Pending</td>
                                <td>Pending</td>
                            @endif
                        </tr>
                        @php
                            $btn_counter++;
                        @endphp                                     
                    @endforeach
                </tbody>
            </table>
        </div>
    </fieldset>
</div>