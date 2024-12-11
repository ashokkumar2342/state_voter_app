<div class="col-lg-12 text-right">
    <button type="button" class="btn btn-default pull-right" onclick="$('#block_select_box').trigger('change');" style="margin:5px;background: #6c757d;color: #fff;"><i class="fa fa-refresh"></i> Refresh</button>
</div>
<div class="col-lg-12">
    <fieldset class="fieldset_border">
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover" id="ajax_data_table">
                <thead class="thead-dark">
                    <tr> 
                        <th>Sr. No.</th>
                        <th>Panchayat MC's</th>
                        <th>Ward No.</th> 
                        <th>Booth No.</th> 
                        <th>Submit Time</th>
                        <th>Expected Time to Process</th>
                        <th>Download</th>
                    </tr>
                </thead>
                @php
                    $srno = 1;
                @endphp
                <tbody> 
                    @foreach ($voterlistprocesseds as $voterlistprocessed)
                        <tr> 
                            <td>{{ $srno++}}</td>
                            <td>{{ $voterlistprocessed->name_e}}</td>
                            <td>{{ $voterlistprocessed->ward_no}}</td> 
                            <td>{{ $voterlistprocessed->booth_no}}</td> 
                            <td>{{ $voterlistprocessed->submit_time}}</td>
                            <td>{{ $voterlistprocessed->expected_time_start}}</td>
                            @if ($voterlistprocessed->status==1)
                                <td><a type="button" target="_blank" href="{{ route('admin.prepare.voter.slip.result.download', Crypt::encrypt($voterlistprocessed->id)) }}" title="">Download</a></td>
                            @elseif ($voterlistprocessed->status==2)
                                <td>Processing</td>
                            @else
                                <td>Pending</td>
                            @endif
                        </tr>                                     
                    @endforeach
                </tbody>
            </table>
        </div>
    </fieldset>
</div>